<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\CredentialReminder;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function showEmailForm()
    {
        return view('emailcheck');
    }

    public function sendCredentials(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'Email tidak terdaftar dalam sistem kami.');
        }
        
        $newPassword = "password";
        
        $user->password = Hash::make($newPassword);
        $user->save();
        
        try {
            Mail::to($user->email)->send(new CredentialReminder($user, $newPassword));
            \Log::info('Email berhasil dikirim ke: ' . $user->email);
            return back()->with('success', 'Informasi login telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            \Log::error('Email error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengirim email. Silakan coba lagi nanti.');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}