<?php

/**
 * ====================================
 * DYNAMIC EMAIL VERIFICATION GUIDE
 * ====================================
 *
 * This file demonstrates how to use the updated
 * SignupVerificationMail and LoginVerificationMail
 * Mailable classes with dynamic greeting and messages.
 */

// ============================================
// USAGE EXAMPLE 1: In AuthenticatedSessionController
// ============================================

namespace App\Http\Controllers\Auth;

use App\Mail\LoginVerificationMail;
use App\Mail\SignupVerificationMail;
use Illuminate\Support\Facades\Mail;

class AuthenticatedSessionController extends Controller
{
    /**
     * Send signup verification email with dynamic content
     * Example: For a tourist signing up
     */
    public function sendSignupEmail(string $email, string $otp, string $role = 'tourist'): bool
    {
        try {
            Mail::send(new SignupVerificationMail(
                verificationUrl: route('login.otp', ['token' => $token]),
                email: $email,
                otp: $otp,
                role: $role // 'tourist' or 'guide'
            ));

            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send signup email: {$e->getMessage()}");

            return false;
        }
    }

    /**
     * Send login verification email with dynamic content
     * Example: For a tour guide logging in
     */
    public function sendLoginEmail(string $email, string $otp, string $role = 'guide'): bool
    {
        try {
            Mail::send(new LoginVerificationMail(
                verificationUrl: route('login.otp', ['token' => $token]),
                email: $email,
                otp: $otp,
                role: $role // 'tourist' or 'guide'
            ));

            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send login email: {$e->getMessage()}");

            return false;
        }
    }
}

// ============================================
// USAGE EXAMPLE 2: Integrate with GmailMailService
// ============================================

/**
 * If you want to keep using GmailMailService but make it dynamic,
 * update the sendOTP method to accept the role and action type:
 */

namespace App\Services;

use App\Mail\LoginVerificationMail;
use App\Mail\SignupVerificationMail;
use Illuminate\Support\Facades\Mail;

class GmailMailService
{
    /**
     * Send signup verification OTP
     */
    public function sendSignupOTP(
        string $email,
        string $otpCode,
        string $role = 'tourist'
    ): bool {
        try {
            Mail::send(new SignupVerificationMail(
                verificationUrl: '', // Not needed for OTP emails
                email: $email,
                otp: $otpCode,
                role: $role
            ));

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send signup OTP', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send login verification OTP
     */
    public function sendLoginOTP(
        string $email,
        string $otpCode,
        string $role = 'tourist'
    ): bool {
        try {
            Mail::send(new LoginVerificationMail(
                verificationUrl: '', // Not needed for OTP emails
                email: $email,
                otp: $otpCode,
                role: $role
            ));

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send login OTP', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}

// ============================================
// USAGE EXAMPLE 3: Updated Controller Method
// ============================================

/**
 * Update your sendOtpForDraft method to include the role:
 */
class AuthenticatedSessionController extends Controller
{
    private function sendOtpForDraft(object $draft, string $actionType = 'login'): bool
    {
        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('login_drafts')
            ->where('id', $draft->id)
            ->update([
                'otp_code' => $otpCode,
                'otp_expires_at' => now()->addMinutes(15),
                'otp_sent_at' => now(),
                'updated_at' => now(),
            ]);

        // Get user role if available (default to 'tourist')
        $user = User::where('email', $draft->email)->first();
        $role = $user?->role ?? 'tourist';

        $mailService = app(GmailMailService::class);

        // Send appropriate email based on action
        if ($actionType === 'signup') {
            return $mailService->sendSignupOTP($draft->email, $otpCode, $role);
        }

        return $mailService->sendLoginOTP($draft->email, $otpCode, $role);
    }
}

// ============================================
// EMAIL TEMPLATE OUTPUT EXAMPLES
// ============================================

/**
 * EXAMPLE 1: Tourist Signup
 *
 * ┌─────────────────────────────────────┐
 * │ Email Verification                  │
 * │                                     │
 * │ Hello Traveler,                     │
 * │                                     │
 * │ Thank you for signing up for        │
 * │ TrblTours! To verify your email     │
 * │ address, please use the             │
 * │ verification code below:            │
 * │                                     │
 * │ ┌──────────────┐                   │
 * │ │   719882     │                   │
 * │ └──────────────┘                   │
 * │                                     │
 * │ This code will expire in 15 minutes.│
 * └─────────────────────────────────────┘
 */

/**
 * EXAMPLE 2: Tour Guide Signup
 *
 * ┌─────────────────────────────────────┐
 * │ Email Verification                  │
 * │                                     │
 * │ Hello Tour Guide,                   │
 * │                                     │
 * │ Thank you for signing up for        │
 * │ TrblTours! To verify your email     │
 * │ address, please use the             │
 * │ verification code below:            │
 * │                                     │
 * │ ┌──────────────┐                   │
 * │ │   719882     │                   │
 * │ └──────────────┘                   │
 * │                                     │
 * │ This code will expire in 15 minutes.│
 * └─────────────────────────────────────┘
 */

/**
 * EXAMPLE 3: Tourist Login
 *
 * ┌─────────────────────────────────────┐
 * │ Email Verification                  │
 * │                                     │
 * │ Hello Traveler,                     │
 * │                                     │
 * │ Thank you for logging in to         │
 * │ TrblTours. Please use the           │
 * │ verification code below to continue:│
 * │                                     │
 * │ ┌──────────────┐                   │
 * │ │   719882     │                   │
 * │ └──────────────┘                   │
 * │                                     │
 * │ This code will expire in 15 minutes.│
 * └─────────────────────────────────────┘
 */

/**
 * EXAMPLE 4: Tour Guide Login
 *
 * ┌─────────────────────────────────────┐
 * │ Email Verification                  │
 * │                                     │
 * │ Hello Tour Guide,                   │
 * │                                     │
 * │ Thank you for logging in to         │
 * │ TrblTours. Please use the           │
 * │ verification code below to continue:│
 * │                                     │
 * │ ┌──────────────┐                   │
 * │ │   719882     │                   │
 * │ └──────────────┘                   │
 * │                                     │
 * │ This code will expire in 15 minutes.│
 * └─────────────────────────────────────┘
 */
