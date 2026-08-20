<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * HardwareCryptoService
 * 
 * Provides cryptographic operations for IoT hardware devices (ESP32/Raspberry Pi):
 * 1. ECDSA Digital Signature generation and verification (secp256r1/prime256v1 curve).
 * 2. AES-256-GCM symmetric payload encryption and decryption with authentication tag.
 * 3. Timestamp anti-replay window validation.
 */
class HardwareCryptoService
{
    /**
     * Default cryptographic key / secret for AES-256-GCM.
     */
    protected string $aesSecret;

    public function __construct()
    {
        // 32-byte key derived from app secret or default fallback
        $rawKey = config('app.key') ?? 'EcoSyncSecureHardwareKey32Bytes!';
        $this->aesSecret = hash('sha256', $rawKey, true); // Always 32 bytes
    }

    /**
     * Generate an ECDSA (prime256v1) KeyPair for a physical IoT device.
     */
    public function generateKeyPair(): array
    {
        $config = [
            'private_key_type' => OPENSSL_KEYTYPE_EC,
            'curve_name' => 'prime256v1',
        ];

        $res = openssl_pkey_new($config);
        if (!$res) {
            throw new Exception("Failed to generate ECDSA keypair: " . openssl_error_string());
        }

        openssl_pkey_export($res, $privateKey);
        $details = openssl_pkey_get_details($res);
        $publicKey = $details['key'];

        return [
            'private_key' => $privateKey,
            'public_key' => $publicKey,
            'algorithm' => 'ECDSA-SHA256 (prime256v1)',
        ];
    }

    /**
     * Sign data using ECDSA private key (SHA256).
     */
    public function signPayload(string $data, string $privateKeyPem): string
    {
        $pkey = openssl_pkey_get_private($privateKeyPem);
        if (!$pkey) {
            throw new Exception("Invalid private key provided.");
        }

        $success = openssl_sign($data, $signature, $pkey, OPENSSL_ALGO_SHA256);
        if (!$success) {
            throw new Exception("Signing failed: " . openssl_error_string());
        }

        return base64_encode($signature);
    }

    /**
     * Verify ECDSA signature of data using public key.
     */
    public function verifySignature(string $data, string $signatureBase64, string $publicKeyPem): bool
    {
        $pkey = openssl_pkey_get_public($publicKeyPem);
        if (!$pkey) {
            Log::warning("HardwareCryptoService: Invalid public key format.");
            return false;
        }

        $signature = base64_decode($signatureBase64);
        $result = openssl_verify($data, $signature, $pkey, OPENSSL_ALGO_SHA256);

        return $result === 1;
    }

    /**
     * Encrypt a JSON/Array payload using AES-256-GCM.
     */
    public function encryptPayload(array $payload, ?string $secretKey = null): array
    {
        $key = $secretKey ? hash('sha256', $secretKey, true) : $this->aesSecret;
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES);
        
        // 12-byte IV standard for GCM mode
        $iv = random_bytes(12);
        $tag = '';

        $ciphertext = openssl_encrypt(
            $json,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '', // AAD (Additional Authenticated Data) optional
            16  // Tag length 16 bytes
        );

        if ($ciphertext === false) {
            throw new Exception("AES-256-GCM Encryption failed.");
        }

        return [
            'ciphertext' => base64_encode($ciphertext),
            'iv' => base64_encode($iv),
            'tag' => base64_encode($tag),
            'cipher' => 'AES-256-GCM',
        ];
    }

    /**
     * Decrypt an AES-256-GCM encrypted payload.
     */
    public function decryptPayload(string $ciphertextBase64, string $ivBase64, string $tagBase64, ?string $secretKey = null): ?array
    {
        $key = $secretKey ? hash('sha256', $secretKey, true) : $this->aesSecret;
        $ciphertext = base64_decode($ciphertextBase64);
        $iv = base64_decode($ivBase64);
        $tag = base64_decode($tagBase64);

        $decrypted = openssl_decrypt(
            $ciphertext,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($decrypted === false) {
            Log::warning("HardwareCryptoService: Decryption or Tag verification failed.");
            return null;
        }

        return json_decode($decrypted, true);
    }

    /**
     * Validate timestamp freshness to prevent Replay Attacks (±300 seconds).
     */
    public function validateTimestamp(int|string $timestamp, int $maxSkewSeconds = 300): bool
    {
        $ts = is_numeric($timestamp) ? (int)$timestamp : strtotime($timestamp);
        if (!$ts) {
            return false;
        }

        $now = time();
        return abs($now - $ts) <= $maxSkewSeconds;
    }

    /**
     * Get system-wide hardware security diagnostic info.
     */
    public function getDiagnosticInfo(): array
    {
        $openSslAvailable = extension_loaded('openssl');
        $supportedCiphers = $openSslAvailable ? openssl_get_cipher_methods() : [];
        $hasAesGcm = in_array('aes-256-gcm', $supportedCiphers);

        return [
            'openssl_installed' => $openSslAvailable,
            'aes_256_gcm_supported' => $hasAesGcm,
            'ecdsa_supported' => $openSslAvailable && defined('OPENSSL_KEYTYPE_EC'),
            'signature_algorithm' => 'ECDSA-SHA256 (prime256v1 / secp256r1)',
            'encryption_algorithm' => 'AES-256-GCM (12-byte IV, 16-byte Tag)',
            'replay_protection_window' => '±300 seconds (5 mins)',
            'status' => ($openSslAvailable && $hasAesGcm) ? 'ACTIVE' : 'DEGRADED',
        ];
    }
}
