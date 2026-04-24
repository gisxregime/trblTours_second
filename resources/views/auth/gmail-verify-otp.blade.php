<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>{{ config('app.name', 'TrblTours') }} - Verify OTP</title>

                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Asimovian:wght@400;700&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

                @vite(['resources/css/app.css', 'resources/js/app.js'])

                <style>
                    * { box-sizing: border-box; }
                    body {
                        margin: 0;
                        min-height: 100vh;
                        font-family: 'Instrument Sans', sans-serif;
                        background: linear-gradient(rgba(212, 165, 99, 0.72), rgba(184, 195, 136, 0.56)), url('/images/signup_login_bg.jpg') center/cover no-repeat fixed;
                        color: #6f5d52;
                    }
                    .brand-header { position: fixed; top: clamp(10px, 2.2vw, 24px); left: clamp(10px, 2.2vw, 24px); display: flex; align-items: center; gap: 12px; z-index: 100; }
                    .brand-icon { width: clamp(34px, 4.2vw, 50px); height: clamp(34px, 4.2vw, 50px); border-radius: 10px; object-fit: cover; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4); }
                    .brand-text { font-family: 'Asimovian', sans-serif; font-size: clamp(14px, 1.8vw, 20px); font-weight: 700; color: #fffaf0; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5), 0 4px 12px rgba(0, 0, 0, 0.4); }
                    .panel { width: min(100%, 560px); background: rgba(255, 251, 244, 0.98); border-radius: 20px; box-shadow: 0 20px 60px rgba(101, 67, 33, 0.22); padding: 34px; border: 1px solid rgba(139, 69, 19, 0.08); backdrop-filter: blur(10px); }
                    .back-link { display: inline-flex; align-items: center; gap: 6px; border: 1px solid #d8c9b5; background: #f4efe6; border-radius: 9999px; padding: 6px 12px; color: #3f2d1f; text-decoration: none; font-size: 13px; font-weight: 600; }
                    h1 { margin-top: 14px; font-size: 28px; font-weight: 600; color: #23170f; }
                    .subtitle { margin-top: 6px; font-size: 14px; color: #584637; }
                    .alert { margin-top: 16px; border-radius: 12px; padding: 12px 14px; font-size: 14px; }
                    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
                    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
                    .alert-info { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
                    .field { margin-top: 16px; }
                    label { display: block; margin-bottom: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #5c4a3a; }
                    input { width: 100%; border: 1px solid #d8c9b5; border-radius: 8px; padding: 12px; background: #f4efe6; color: #2f241a; font: inherit; }
                    .actions { margin-top: 16px; display: grid; gap: 10px; }
                    .primary-btn,.secondary-btn { width: 100%; border: 0; border-radius: 9999px; padding: 12px 16px; font-size: 15px; font-weight: 700; cursor: pointer; }
                    .primary-btn { background: #8b4e1c; color: #f8efe3; }
                    .secondary-btn { background: #f7efe1; color: #6f5d46; border: 1px solid #d8c9b5; }
                    .links { margin-top: 14px; text-align: center; font-size: 14px; }
                    .links a { color: #4b3828; font-weight: 600; text-decoration: none; }
                    .links a:hover { text-decoration: underline; }
                </style>

            <div class="form-group">
                <div class="brand-header">
                    <img src="{{ asset('images/tribaltours_icon.png') }}" alt="TrblTours" class="brand-icon">
                    <div class="brand-text">TrblTours</div>
                </div>

                <main class="min-h-screen flex items-center justify-center px-4 py-8">
                    <section class="panel">
                        <a href="{{ url('/') }}" class="back-link">
                            <i class="fas fa-arrow-left text-[10px]" aria-hidden="true"></i>
                            <span>Back to Home</span>
                        </a>

                        <h1>Verify your email</h1>
                        <p class="subtitle">Enter the 6-digit code we sent through Gmail.</p>

                        @if ($errors->any())
                            <div class="alert alert-error">{{ $errors->first() }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-error">{{ session('error') }}</div>
                        @endif

                        @if (session('message'))
                            <div class="alert alert-info">{{ session('message') }}</div>
                        @endif

                        <form method="POST" action="{{ route('verify.otp') }}">
                            @csrf

                            <div class="field">
                                <label for="email">Email Address</label>
                                <input id="email" type="email" name="email" value="{{ old('email', session('email')) }}" required autofocus>
                            </div>

                            <div class="field">
                                <label for="otp_code">Verification Code</label>
                                <input id="otp_code" type="text" name="otp_code" placeholder="000000" maxlength="6" pattern="[0-9]{6}" required>
                            </div>

                            <div class="actions">
                                <button type="submit" class="primary-btn">Verify & Continue</button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('resend.otp') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ old('email', session('email')) }}">
                            <div class="actions">
                                <button type="submit" class="secondary-btn">Resend Code</button>
                            </div>
                        </form>

                        <div class="links">
                            <a href="{{ route('gmail.register') }}">← Back to Registration</a>
                        </div>
                    </section>
                </main>
                <input type="email" id="email" name="email" value="{{ old('email', session('email')) }}" required autofocus>
                @if ($errors->has('email'))
                    <div class="errors">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="otp_code">Verification Code</label>
                <input type="text" id="otp_code" name="otp_code" placeholder="000000" maxlength="6" pattern="[0-9]{6}" required>
                @if ($errors->has('otp_code'))
                    <div class="errors">{{ $errors->first('otp_code') }}</div>
                @endif
            </div>

            <button type="submit">Verify & Continue</button>
        </form>

        <form method="POST" action="{{ route('resend.otp') }}" style="margin-top: 10px;">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', session('email')) }}">
            <button type="submit" class="secondary-btn">Resend Code</button>
        </form>

        <div class="links">
            <p><a href="{{ route('gmail.register') }}">← Back to Registration</a></p>
        </div>
    </div>
</body>
</html>
