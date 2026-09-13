<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        if (Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            return redirect()->intended(
                route('admin.dashboard')
            );
        }

        return back()
            ->withErrors([
                'email' => 'The email or password is incorrect.',
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Logged out successfully.');
    }
}
