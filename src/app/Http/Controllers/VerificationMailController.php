<?php

namespace App\Http\Controllers;

use App\Mail\IdentityVerificationMail;
use App\Models\MailHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class VerificationMailController extends Controller
{
    public function send($userId)
    {
        try {
            $user = User::findOrFail($userId);

            Log::debug('Sending verification email', [
                'user' => $user,
                'mail_config' => [
                    'driver' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'post' => config('mail.mailers.smtp.port'),
                ]
            ]);

            $user->sendEmailVerificationNotification();

            MailHistory::create([
                'user_id' => $userId,
                'sent_at' => now(),
                'status' => 'success'
            ]);

            return response()->json(['message' => '送信が完了しました']);
        } catch (\Exception $e) {
            Log::error('Failed to send verification email', [
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);
            return response()->json(['message' => 'メール送信に失敗しました'], 500);
        }
    }

    public function resend(Request $request)
    {
        Log::debug('Verification resend requested', [
            'user' => auth()->user(),
            'email' => auth()->user()->email
        ]);

        $request->user()->with('status', 'verification-link-sent');
    }
}
