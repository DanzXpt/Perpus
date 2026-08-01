<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;

class AuthController extends Controller
{
    // ==========================================
    // 1. LOGIN
    // ==========================================
    
    // Tampilkan Form Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Login
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // ==========================================
    // 2. REGISTER
    // ==========================================

    // Tampilkan Form Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses Register
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // ==========================================
    // 3. LOGOUT
    // ==========================================

    // Proses Logout
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ==========================================
    // 4. LUPA & RESET PASSWORD
    // ==========================================

    // Tampilkan Form "Lupa Password"
    public function showForgetPassword()
    {
        return view('auth.forgot-password');
    }

    // Kirim Link/Token Reset ke Email
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }

    // Tampilkan Form "Reset Password" Baru
    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    // Proses Simpan Password Baru (Dengan Konfirmasi)
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}