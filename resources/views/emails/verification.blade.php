<x-mail::message>
{{-- Header --}}
# Email Verification

{{-- Dynamic Greeting --}}
{{ $greeting }}

{{-- Dynamic Message Based on Action Type --}}
{{ $messageLine }}

<x-mail::panel>
{{ $otp }}
</x-mail::panel>

This code will expire in **15 minutes**.

If you didn't request this verification code, please ignore this email.

---

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
