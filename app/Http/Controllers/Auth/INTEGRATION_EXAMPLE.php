<?php

namespace App\Http\Controllers\Auth;

/**
 * ============================================
 * COMPLETE INTEGRATION EXAMPLE
 * ============================================
 *
 * Copy and paste the sendOtpForDraft method
 * into your AuthenticatedSessionController
 * to use the new dynamic email verification.
 *
 * This example shows how to integrate
 * SignupVerificationMail and LoginVerificationMail
 * with role-based dynamic content.
 */

use App\Mail\LoginVerificationMail;
use App\Mail\SignupVerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthenticatedSessionControllerIntegrationExample
{
    /**
     * UPDATED METHOD: Send OTP for signup/login draft
     *
     * PARAMETERS:
     * - $draft: Login draft object from database
     * - $actionType: 'signup' or 'login' (defaults to 'login')
     *
     * RETURNS:
     * - bool: true if email sent successfully, false otherwise
     */
    private function sendOtpForDraft(object $draft, string $actionType = 'login'): bool
    {
        // Generate 6-digit OTP code
        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update login draft with OTP details
        DB::table('login_drafts')
            ->where('id', $draft->id)
            ->update([
                'otp_code' => $otpCode,
                'otp_expires_at' => now()->addMinutes(15),
                'otp_sent_at' => now(),
                'updated_at' => now(),
            ]);

        // Get user to determine role
        $user = User::where('email', $draft->email)->first();
        $role = $user?->role ?? 'tourist'; // Default to 'tourist' if not found

        try {
            // Send appropriate email based on action type
            if ($actionType === 'signup') {
                Mail::send(new SignupVerificationMail(
                    verificationUrl: route('login.otp', ['token' => $draft->token]),
                    email: $draft->email,
                    otp: $otpCode,
                    role: $role
                ));
            } else {
                Mail::send(new LoginVerificationMail(
                    verificationUrl: route('login.otp', ['token' => $draft->token]),
                    email: $draft->email,
                    otp: $otpCode,
                    role: $role
                ));
            }

            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send {$actionType} OTP email", [
                'email' => $draft->email,
                'role' => $role,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * EXAMPLE USAGE IN storeEmail METHOD
     *
     * This shows where to call sendOtpForDraft in your
     * existing storeEmail method for login flow.
     */
    public function storeEmail_ExampleUsage($request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $token = (string) Str::uuid();
        $now = now();

        $draft = DB::table('login_drafts')->updateOrInsert(
            ['email' => $validated['email']],
            [
                'token' => $token,
                'email' => $validated['email'],
                'role_selected_at' => null,
                'email_verified_at' => null,
                'otp_code' => null,
                'otp_expires_at' => null,
                'otp_sent_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );

        // Get the draft object
        $draftObject = DB::table('login_drafts')
            ->where('email', $validated['email'])
            ->first();

        // Send OTP for LOGIN (pass 'signup' if this is a signup flow)
        if ($draftObject === null || ! $this->sendOtpForDraft($draftObject, 'login')) {
            return back()->withErrors(['email' => 'Failed to send verification code. Please try again.']);
        }

        return redirect()->route('login.otp', ['token' => $token])
            ->with('status', 'If this email exists in our records, we sent a verification code.');
    }

    /**
     * EXAMPLE FOR SIGNUP FLOW
     *
     * If you have a separate signup OTP method, use it like this:
     */
    public function storeSignupEmail_ExampleUsage($request)
    {
        // ... validation code ...

        $draftObject = DB::table('login_drafts')
            ->where('email', $validated['email'])
            ->first();

        // Send OTP for SIGNUP
        if ($draftObject === null || ! $this->sendOtpForDraft($draftObject, 'signup')) {
            return back()->withErrors(['email' => 'Failed to send verification code. Please try again.']);
        }

        // ... redirect code ...
    }
}

/**
 * ============================================
 * QUICK REFERENCE: DYNAMIC CONTENT GENERATED
 * ============================================
 *
 * Based on $role and $actionType, the email
 * will automatically contain:
 *
 * TOURIST + SIGNUP:
 *   Greeting: "Hello Traveler,"
 *   Message: "Thank you for signing up for TrblTours! To verify your email address, please use the verification code below:"
 *
 * TOURIST + LOGIN:
 *   Greeting: "Hello Traveler,"
 *   Message: "Thank you for logging in to TrblTours. Please use the verification code below to continue:"
 *
 * GUIDE + SIGNUP:
 *   Greeting: "Hello Tour Guide,"
 *   Message: "Thank you for signing up for TrblTours! To verify your email address, please use the verification code below:"
 *
 * GUIDE + LOGIN:
 *   Greeting: "Hello Tour Guide,"
 *   Message: "Thank you for logging in to TrblTours. Please use the verification code below to continue:"
 */

/**
 * ============================================
 * DEBUGGING: LOG USER ROLE
 * ============================================
 *
 * If emails aren't sending with the correct
 * role, add this debug code:
 */

// Temporary debug code
$user = User::where('email', $draft->email)->first();
\Log::info("Sending {$actionType} email", [
    'email' => $draft->email,
    'role' => $user?->role ?? 'not-found',
    'user_exists' => (bool) $user,
]);

/**
 * ============================================
 * ERROR HANDLING
 * ============================================
 *
 * The method includes try-catch to handle:
 * - Mail service failures
 * - Invalid email addresses
 * - Role lookup failures
 *
 * Errors are logged with full context for debugging.
 */
