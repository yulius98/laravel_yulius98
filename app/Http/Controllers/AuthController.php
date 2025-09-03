<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        $result = $this->authService->attemptLogin($credentials);

        if (!$result['success']) {
            return back()->withErrors([
                'username' => $result['message'],
            ])->onlyInput('username');
        }

        $request->session()->regenerate();
        session(['dtuser' => $result['user']]);

        return redirect($result['redirect']);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request);
        return redirect('/');
    }
}
