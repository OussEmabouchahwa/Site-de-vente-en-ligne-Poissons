<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Check if the user has already verified their email
        if ($request->user()->hasVerifiedEmail()) {
            Log::info('User attempted to verify already verified email', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email
            ]);

            return redirect()->intended(route('dashboard'))
                ->with('status', 'Votre email est déjà vérifié.');
        }

        // Mark the email as verified
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            // Log successful email verification
            Log::info('Email verified successfully', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email
            ]);

            return redirect()->intended(route('dashboard'))
                ->with('status', 'Votre email a été vérifié avec succès.');
        }

        // Log verification failure
        Log::warning('Email verification failed', [
            'user_id' => $request->user()->id,
            'email' => $request->user()->email
        ]);

        return redirect()->route('verification.notice')
            ->with('error', 'Impossible de vérifier votre email. Veuillez réessayer.');
    }
}
