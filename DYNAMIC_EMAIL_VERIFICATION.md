# Dynamic Email Verification Implementation

## Overview

This implementation provides dynamic email verification with personalized greetings and messages based on:
- **User Role**: Tourist vs Tour Guide
- **Action Type**: Signup vs Login

## Files Modified

### 1. **Mailable Classes**

#### `app/Mail/SignupVerificationMail.php`
- **Accepts Parameters**:
  - `verificationUrl` (string)
  - `email` (string)
  - `otp` (string) - New
  - `role` (string) - New: 'tourist' or 'guide'

- **Generates Dynamically**:
  - `$greeting` - "Hello Traveler," or "Hello Tour Guide,"
  - `$messageLine` - Signup-specific message

```php
// Usage
new SignupVerificationMail(
    verificationUrl: $url,
    email: 'user@example.com',
    otp: '719882',
    role: 'tourist' // or 'guide'
)
```

#### `app/Mail/LoginVerificationMail.php`
- **Accepts Parameters**:
  - `verificationUrl` (string)
  - `email` (string)
  - `otp` (string) - New
  - `role` (string) - New: 'tourist' or 'guide'

- **Generates Dynamically**:
  - `$greeting` - "Hello Traveler," or "Hello Tour Guide,"
  - `$messageLine` - Login-specific message

```php
// Usage
new LoginVerificationMail(
    verificationUrl: $url,
    email: 'user@example.com',
    otp: '719882',
    role: 'guide' // or 'tourist'
)
```

### 2. **Email Template**

#### `resources/views/emails/verification.blade.php`
Unified template used by both Signup and Login Mailable classes.

**Dynamic Variables**:
- `{{ $greeting }}` - Personalized greeting
- `{{ $messageLine }}` - Action-specific message
- `{{ $otp }}` - 6-digit verification code

## Dynamic Content Mapping

| Scenario | Greeting | Message |
|----------|----------|---------|
| **Tourist Signup** | Hello Traveler, | Thank you for signing up for TrblTours! To verify your email address, please use the verification code below: |
| **Guide Signup** | Hello Tour Guide, | Thank you for signing up for TrblTours! To verify your email address, please use the verification code below: |
| **Tourist Login** | Hello Traveler, | Thank you for logging in to TrblTours. Please use the verification code below to continue: |
| **Guide Login** | Hello Tour Guide, | Thank you for logging in to TrblTours. Please use the verification code below to continue: |

## Integration Steps

### Step 1: Update Controllers

In `app/Http/Controllers/Auth/AuthenticatedSessionController.php`:

```php
use App\Mail\SignupVerificationMail;
use App\Mail\LoginVerificationMail;
use Illuminate\Support\Facades\Mail;

// For signup
Mail::send(new SignupVerificationMail(
    verificationUrl: route('login.otp', ['token' => $token]),
    email: $userEmail,
    otp: $otpCode,
    role: $user->role // 'tourist' or 'guide'
));

// For login
Mail::send(new LoginVerificationMail(
    verificationUrl: route('login.otp', ['token' => $token]),
    email: $userEmail,
    otp: $otpCode,
    role: $user->role // 'tourist' or 'guide'
));
```

### Step 2: Get User Role

When sending emails, retrieve the user's role from the database:

```php
$user = User::where('email', $email)->first();
$role = $user?->role ?? 'tourist'; // Default to 'tourist'
```

### Step 3: Update `sendOtpForDraft` Method

```php
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

    // Get user role
    $user = User::where('email', $draft->email)->first();
    $role = $user?->role ?? 'tourist';

    // Send appropriate email
    if ($actionType === 'signup') {
        Mail::send(new SignupVerificationMail(
            verificationUrl: '',
            email: $draft->email,
            otp: $otpCode,
            role: $role
        ));
    } else {
        Mail::send(new LoginVerificationMail(
            verificationUrl: '',
            email: $draft->email,
            otp: $otpCode,
            role: $role
        ));
    }

    return true;
}
```

## Email Output Examples

### Tourist Signup Email
```
═══════════════════════════════════════
 Email Verification

 Hello Traveler,

 Thank you for signing up for TrblTours!
 To verify your email address, please
 use the verification code below:

 ┌──────────────┐
 │   719882     │
 └──────────────┘

 This code will expire in 15 minutes.

 If you didn't request this verification
 code, please ignore this email.

 Thanks,
 TrblTours
═══════════════════════════════════════
```

### Tour Guide Login Email
```
═══════════════════════════════════════
 Email Verification

 Hello Tour Guide,

 Thank you for logging in to TrblTours.
 Please use the verification code below
 to continue:

 ┌──────────────┐
 │   719882     │
 └──────────────┘

 This code will expire in 15 minutes.

 If you didn't request this verification
 code, please ignore this email.

 Thanks,
 TrblTours
═══════════════════════════════════════
```

## Optional: Keep GmailMailService Integration

If you prefer to keep using `GmailMailService`, extend it to support dynamic emails:

```php
namespace App\Services;

use App\Mail\SignupVerificationMail;
use App\Mail\LoginVerificationMail;
use Illuminate\Support\Facades\Mail;

class GmailMailService
{
    public function sendSignupOTP(string $email, string $otp, string $role = 'tourist'): bool
    {
        Mail::send(new SignupVerificationMail(
            verificationUrl: '',
            email: $email,
            otp: $otp,
            role: $role
        ));
        return true;
    }

    public function sendLoginOTP(string $email, string $otp, string $role = 'tourist'): bool
    {
        Mail::send(new LoginVerificationMail(
            verificationUrl: '',
            email: $email,
            otp: $otp,
            role: $role
        ));
        return true;
    }
}
```

Then use it:
```php
$mailService = app(GmailMailService::class);
$mailService->sendSignupOTP('user@example.com', '719882', 'guide');
```

## Benefits

✅ **Personalized Experience** - Users see role-specific greetings  
✅ **Clear Communication** - Messages explain action context (signup vs login)  
✅ **Clean Architecture** - Dynamic content in Mailable classes, not templates  
✅ **Reusable** - Single template for both signup and login  
✅ **Professional** - Uses "Traveler" instead of "Tourist"  
✅ **Maintainable** - Easy to update messages in one place  

## Testing

Create a test to verify dynamic content:

```php
// tests/Feature/EmailVerificationTest.php
public function test_signup_email_for_tourist()
{
    Mail::fake();
    
    Mail::send(new SignupVerificationMail(
        verificationUrl: 'https://example.com',
        email: 'tourist@example.com',
        otp: '123456',
        role: 'tourist'
    ));
    
    Mail::assertSent(SignupVerificationMail::class, function ($mail) {
        $html = $mail->render();
        return str_contains($html, 'Hello Traveler,')
            && str_contains($html, 'Thank you for signing up');
    });
}

public function test_login_email_for_guide()
{
    Mail::fake();
    
    Mail::send(new LoginVerificationMail(
        verificationUrl: 'https://example.com',
        email: 'guide@example.com',
        otp: '123456',
        role: 'guide'
    ));
    
    Mail::assertSent(LoginVerificationMail::class, function ($mail) {
        $html = $mail->render();
        return str_contains($html, 'Hello Tour Guide,')
            && str_contains($html, 'Thank you for logging in');
    });
}
```

## Design Maintained

✨ The email UI/design from your screenshot is preserved using Laravel's Mail components (`<x-mail::*>`)

---

**Created**: April 22, 2026  
**TrblTours Email Verification System**
