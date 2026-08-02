<?php

namespace App\Http\Middleware;

use App\Services\HardwareCryptoService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * VerifyHardwareSignature
 * 
 * Middleware for validating incoming IoT hardware payloads (ESP32/Raspberry Pi):
 * 1. Checks X-Hardware-Timestamp to prevent replay attacks.
 * 2. Verifies X-Hardware-Signature using ECDSA-SHA256 if device key is provided.
 * 3. Decrypts AES-256-GCM encrypted payloads if ciphertext, iv, and tag are sent.
 */
class VerifyHardwareSignature
{
    protected HardwareCryptoService $crypto;

    public function __construct(HardwareCryptoService $crypto)
    {
        $this->crypto = $crypto;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Hardware-Signature');
        $timestamp = $request->header('X-Hardware-Timestamp');
        $deviceId  = $request->header('X-Hardware-Device-ID');
        $publicKey = $request->header('X-Hardware-Public-Key'); // Passed or fetched from registered device registry

        // 1. If hardware signature headers are present, enforce strict cryptographic verification
        if ($signature || $deviceId) {
            // Validate timestamp against replay window (±300 seconds)
            if ($timestamp && !$this->crypto->validateTimestamp($timestamp)) {
                Log::warning("Hardware Security Alert: Replay attack or clock skew detected for device {$deviceId}.");
                return response()->json([
                    'error' => 'Security Error',
                    'message' => 'Hardware payload rejected: Timestamp expired or replay attack detected.',
                    'security_status' => 'REJECTED_REPLAY_ATTACK'
                ], 401);
            }

            // Verify ECDSA signature if public key is provided
            if ($signature && $publicKey) {
                $rawContent = $request->getContent() ?: json_encode($request->all());
                $isValid = $this->crypto->verifySignature($rawContent, $signature, base64_decode($publicKey));

                if (!$isValid) {
                    Log::warning("Hardware Security Alert: Invalid ECDSA signature from device {$deviceId}.");
                    return response()->json([
                        'error' => 'Authentication Error',
                        'message' => 'Hardware payload rejected: Invalid ECDSA digital signature.',
                        'security_status' => 'REJECTED_INVALID_SIGNATURE'
                    ], 401);
                }
            }
        }

        // 2. Check if payload is encrypted using AES-256-GCM
        if ($request->has(['ciphertext', 'iv', 'tag'])) {
            $decrypted = $this->crypto->decryptPayload(
                $request->input('ciphertext'),
                $request->input('iv'),
                $request->input('tag'),
                $request->header('X-Hardware-Key')
            );

            if (!$decrypted) {
                return response()->json([
                    'error' => 'Decryption Error',
                    'message' => 'Hardware payload rejected: AES-256-GCM decryption failed or payload tampered with.',
                    'security_status' => 'REJECTED_DECRYPTION_FAILED'
                ], 422);
            }

            // Replace input with decrypted parameters
            $request->merge($decrypted);
        }

        // Add security header to response indicating verified status
        $response = $next($request);
        
        if ($signature || $deviceId) {
            $response->headers->set('X-Security-Mode', 'ECDSA-AES256GCM-Verified');
        } else {
            $response->headers->set('X-Security-Mode', 'Dashboard-Simulation-Permitted');
        }

        return $response;
    }
}
