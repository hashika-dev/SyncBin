<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laragear\TwoFactor\TwoFactorAuthentication;
use Laragear\TwoFactor\Contracts\TwoFactorAuthenticatable as TwoFactorAuthenticatableContract;

class User extends Authenticatable implements TwoFactorAuthenticatableContract
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthentication;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Check if user is SuperAdmin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is Admin or SuperAdmin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'superadmin';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Verifies the Code against the Shared Secret with wide bidirectional window support
     * to handle potential clock skew between server and mobile authenticator apps (up to ±5 mins).
     */
    public function validateCode(string|int $code): bool
    {
        $twoFactor = $this->twoFactorAuth;
        if (!$twoFactor || !$twoFactor->exists) {
            return false;
        }

        $code = preg_replace('/[^0-9]/', '', (string) $code);
        if (strlen($code) !== 6) {
            return false;
        }

        // Allow wide window tolerance (-10 to +10 periods = +/- 5 minutes clock skew)
        $window = 10;

        for ($i = -$window; $i <= $window; $i++) {
            if (hash_equals($twoFactor->makeCode('now', $i), $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
