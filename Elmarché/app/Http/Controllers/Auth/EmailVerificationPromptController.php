<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // Check if email is already verified
        if ($request->user()->hasVerifiedEmail()) {
            Log::info('User accessed verification page with already verified email', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email
            ]);

            return redirect()->intended(route('dashboard', absolute: false))
                ->with('status', 'Votre email est déjà vérifié.');
        }

        // Log verification page access
        Log::info('User accessed email verification page', [
            'user_id' => $request->user()->id,
            'email' => $request->user()->email
        ]);

        // Render verification notice view
        return view('auth.verify-email', [
            'status' => session('status'),
            'resendLink' => route('verification.send')
        ]);
    }
}
