<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
        try {
            Log::debug('Validation attempt in AuthenticatedSessionController');

            $credentials = $request->validated();

            Log::debug('Credentials after validation:', [
                'credentials' => $credentials
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                Log::debug('Login successful', [
                    'user_id' => Auth::id()
                ]);

                return redirect()->intended('dashboard');
            }
            Log::debug('Login filed - invalid credentials');

            return back()->withErrors([
                'email' => 'メールアドレスまたはパスワードが正しくありません。',
            ])->withInput($request->only('email'));
        } catch (Exception $e) {
            Log::error('Login error:', [
                'message' => $e->getMessage()
            ]);

            return back()->withErrors([
                'error' => '認証処理中にエラーが発生しました。'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
