<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class TokenService
{
    public function createToken(array $data): string
    {
        $expiresAt = $this->calculateExpiresAt($data);

        $user = Auth::user();
        $token = $user->createToken($data['token_name'], $data['permissions'])->plainTextToken;

        if ($expiresAt) {
            $user->tokens()->latest()->first()->update(['expires_at' => $expiresAt]);
        }

        return $token;
    }

    private function calculateExpiresAt(array $data): ?\DateTimeInterface
    {
        if (empty($data['expires_option'])) {
            return null;
        }

        return match ($data['expires_option']) {
            'hours' => now()->addHours($data['expires_hours']),
            'weeks' => now()->addWeeks($data['expires_weeks']),
            'custom' => $data['expires_at'],
            default => null,
        };
    }

    public function revokeToken(int $tokenId, int $userId): ?string
    {
        $token = Auth::user()->tokens()->find($tokenId);

        if (! $token) {
            return null;
        }

        $tokenName = $token->name;
        $token->delete();

        return $tokenName;
    }
}
