<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Please enter your email address.',
            'email.email'       => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Incorrect email or password. Please try again.']);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user->isAdmin() && ! $user->hasVerifiedEmail()) {
            // log the user out and send a verification code for non-admin accounts
            Auth::logout();
            $this->generateAndSendCode($user);
            return redirect()->route('verify', ['email' => $user->email])
                ->with('success', 'A verification code was sent to your email. Please enter it to continue.');
        }

        return $this->redirectByRole($user)
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out. See you soon!');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'Please enter your full name.',
            'name.min'           => 'Name must be at least 2 characters.',
            'email.required'     => 'Please enter your email address.',
            'email.email'        => 'Please enter a valid email address.',
            'email.unique'       => 'An account with this email already exists.',
            'password.required'  => 'Please create a password.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'client',
        ]);

        // generate & send code, do NOT auto-login until verified
        $this->generateAndSendCode($user);

        return redirect()->route('verify', ['email' => $user->email])
            ->with('success', 'Account created. Please check your email for the verification code.');
    }

    public function showVerify(Request $request)
    {
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code'  => ['required', 'digits:6'],
        ]);

        $user = User::where('email', $data['email'])->first();
        if (! $user) {
            return back()->withErrors(['email' => 'No account found with this email.']);
        }

        if ($user->hasVerifiedEmail()) {
            Auth::login($user);
            return redirect()->route('home')->with('success', 'Email already verified.');
        }

        if (
            $user->verification_code !== $data['code'] ||
            ! $user->verification_code_expires_at ||
            $user->verification_code_expires_at->isPast()
        ) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $user->forceFill([
            'email_verified_at'            => now(),
            'verification_code'            => null,
            'verification_code_expires_at' => null,
        ])->save();

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectByRole($user)->with('success', 'Email verified successfully.');
    }

    public function resendCode(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'No account found with this email.']);
        }

        if ($user->hasVerifiedEmail()) {
            return back()->with('success', 'Email already verified.');
        }

        $this->generateAndSendCode($user);

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    private function generateAndSendCode(User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'verification_code'            => $code,
            'verification_code_expires_at' => now()->addMinutes(10),
        ])->save();

        Mail::to($user->email)->send(new VerificationCodeMail($user->name, $code));
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */
    private function redirectByRole(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }
}
