<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Notifications\WelcomeUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CreateUser
{
    public function handle(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'note' => $data['note'] ?? null,
            'role' => $data['role'] ?? null,
            'status' => $data['status'] ?? null,
            'password' => Hash::make(Str::password(12)),
            // Bypass email verification for the welcome email
            'email_verified_at' => now(),
        ]);

        // Generate password reset token and send welcome email
        $token = Password::createToken($user);
        $user->notify(new WelcomeUser($token));

        return $user;
    }
}
