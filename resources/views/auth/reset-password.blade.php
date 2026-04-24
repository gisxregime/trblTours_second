<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TrblTours') }} - Reset Password</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asimovian:wght@400;700&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Instrument Sans', sans-serif;
            background: linear-gradient(rgba(212, 165, 99, 0.72), rgba(184, 195, 136, 0.56)), url('/images/signup_login_bg.jpg') center/cover no-repeat fixed;
            color: #6f5d52;
        }

        .brand-header {
            position: fixed;
            top: clamp(10px, 2.2vw, 24px);
            left: clamp(10px, 2.2vw, 24px);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 100;
        }

        .brand-icon {
            width: clamp(34px, 4.2vw, 50px);
            height: clamp(34px, 4.2vw, 50px);
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        .brand-text {
            font-family: 'Asimovian', sans-serif;
            font-size: clamp(14px, 1.8vw, 20px);
            font-weight: 700;
            color: #fffaf0;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5), 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        .panel {
            width: min(100%, 560px);
            background: rgba(255, 251, 244, 0.98);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(101, 67, 33, 0.22);
            padding: 34px;
            border: 1px solid rgba(139, 69, 19, 0.08);
            backdrop-filter: blur(10px);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #d8c9b5;
            background: #f4efe6;
            border-radius: 9999px;
            padding: 6px 12px;
            color: #3f2d1f;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        h1 {
            margin-top: 14px;
            font-size: 28px;
            font-weight: 600;
            color: #23170f;
        }

        .subtitle {
            margin-top: 6px;
            font-size: 14px;
            color: #584637;
        }

        .field {
            margin-top: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #5c4a3a;
        }

        input {
            width: 100%;
            border: 1px solid #d8c9b5;
            border-radius: 8px;
            padding: 12px;
            background: #f4efe6;
            color: #2f241a;
            font: inherit;
        }

        .actions {
            margin-top: 16px;
            display: grid;
            gap: 10px;
        }

        .primary-btn,
        .secondary-btn {
            width: 100%;
            border: 0;
            border-radius: 9999px;
            padding: 12px 16px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .primary-btn { background: #8b4e1c; color: #f8efe3; }
        .secondary-btn { background: #f7efe1; color: #6f5d52; border: 1px solid #d8c9b5; }

        .errors {
            margin-top: 10px;
            color: #991b1b;
            font-size: 14px;
        }
    </style>
</head>
<body>
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

            <h1>Reset your password</h1>
            <p class="subtitle">Choose a new password for your TrblTours account.</p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div style="position: relative;">
                        <input id="password" type="password" name="password" required autocomplete="new-password" style="padding-right: 40px;" data-password-toggle-input="password">
                        <button type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: 0; background: transparent; cursor: pointer; font-size: 12px; font-weight: 700; color: #6f5d52;" data-password-toggle="password">Show</button>
                    </div>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <div style="position: relative;">
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" style="padding-right: 40px;" data-password-toggle-input="password_confirmation">
                        <button type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: 0; background: transparent; cursor: pointer; font-size: 12px; font-weight: 700; color: #6f5d52;" data-password-toggle="password_confirmation">Show</button>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="errors">{{ $errors->first() }}</div>
                @endif

                <div class="actions">
                    <button type="submit" class="primary-btn">Reset Password</button>
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="secondary-btn" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">Back to Login</a>
                    @endif
                </div>
            </form>
        </section>
    </main>
</body>
</html>
