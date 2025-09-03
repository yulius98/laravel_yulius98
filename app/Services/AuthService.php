<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Attempt to authenticate user
     */
    public function attemptLogin(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            return [
                'success' => false,
                'message' => 'The username and/or password is incorrect!',
                'redirect' => null
            ];
        }

        $user = User::where('username', $credentials['username'])->first();

        if ($user->role !== 'admin') {
            Auth::logout();
            return [
                'success' => false,
                'message' => 'You do not have permission to access the next page!',
                'redirect' => null
            ];
        }

        return [
            'success' => true,
            'message' => 'Login successful',
            'redirect' => route('dashboard', ['username' => $user->username]),
            'user' => $user
        ];
    }

    /**
     * Logout user and clean session
     */
    public function logout(Request $request): void
    {
        $request->session()->forget('dtuser');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
