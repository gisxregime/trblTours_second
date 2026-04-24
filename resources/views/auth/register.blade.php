<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TrblTours') }} - Sign Up</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asimovian:wght@400;700&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Instrument Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(rgba(212, 165, 99, 0.72), rgba(184, 195, 136, 0.56)), url('/images/signup_login_bg.jpg') center/cover no-repeat fixed;
            color: #6f5d52;
        }

        .brand-font {
            font-family: 'Asimovian', 'Instrument Sans', sans-serif;
            letter-spacing: 0.02em;
        }

        .brand-header {
            position: fixed;
            top: clamp(10px, 2.2vw, 24px);
            left: clamp(10px, 2.2vw, 24px);
            display: flex;
            align-items: center;
            gap: clamp(8px, 1vw, 12px);
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
            font-size: clamp(14px, 1.8vw, 20px);
            font-weight: 700;
            color: #fffaf0;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5), 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        .container {
            width: min(100%, 700px);
            max-height: calc(100vh - 64px);
            overflow-y: auto;
            background: rgba(255, 251, 244, 0.98);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(101, 67, 33, 0.22);
            padding: 36px;
            border: 1px solid rgba(139, 69, 19, 0.08);
            backdrop-filter: blur(10px);
            scrollbar-width: thin;
            scrollbar-color: #a36f3f rgba(242, 231, 207, 0.58);
        }

        .container::-webkit-scrollbar {
            width: 10px;
        }

        .container::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #c58b52 0%, #8b4513 55%, #6f5d52 100%);
            border-radius: 999px;
            border: 2px solid rgba(255, 250, 240, 0.88);
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
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
            transition: background-color 0.2s ease;
        }

        .back-link:hover {
            background: #ece1d0;
        }

        .title {
            margin-top: 14px;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: #23170f;
        }

        .subtitle {
            margin-top: 6px;
            font-size: 14px;
            color: #584637;
        }

        .role-toggle {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 16px;
            margin-bottom: 20px;
        }

        .role-btn {
            border: 1.5px solid #d8c9b5;
            border-radius: 12px;
            padding: 14px 12px;
            background: #f7efe1;
            color: #6f5d52;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .role-btn.active {
            border-color: #8b4513;
            background: #fff7eb;
            color: #8b4513;
            box-shadow: 0 6px 14px rgba(139, 69, 19, 0.12);
        }

        .role-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            margin: 0 auto 8px;
            border-radius: 8px;
            background: rgba(139, 69, 19, 0.1);
            color: #8b4513;
            font-size: 28px;
        }

        .section-title {
            margin: 18px 0 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid #d4c5b2;
            color: #604c3a;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            font-size: 11px;
            font-weight: 700;
        }

        .alert {
            margin-bottom: 16px;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
        }

        .alert.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert.success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .form-grid {
            display: grid;
            gap: 16px;
        }

        .field {
            display: grid;
            gap: 6px;
        }

        label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #5c4a3a;
        }

        .required::after {
            content: ' *';
            color: #9a4f1d;
        }

        input,
        select {
            width: 100%;
            border: 1px solid #d8c9b5;
            border-radius: 8px;
            padding: 10px 12px;
            background: #f4efe6;
            color: #2f241a;
            font: inherit;
            font-size: 14px;
            outline: none;
        }

        input::placeholder {
            color: #958067;
        }

        input:focus,
        select:focus {
            border-color: #9a5f2a;
        }

        .input-row {
            display: grid;
            gap: 16px;
        }

        @media (min-width: 640px) {
            .input-row.two {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .submit-btn {
            width: 100%;
            border: 0;
            border-radius: 9999px;
            padding: 12px 16px;
            background: #8b4e1c;
            color: #f8efe3;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .submit-btn:hover {
            background: #764116;
            transform: translateY(-1px);
        }

        .footer-links {
            margin-top: 14px;
            text-align: center;
            font-size: 14px;
            color: #6f5b46;
        }

        .footer-links a {
            color: #4b3828;
            font-weight: 600;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .secondary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            border-radius: 9999px;
            border: 1px solid #d8c9b5;
            background: #f7efe1;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 700;
            color: #6f5d52;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="brand-header">
        <img src="{{ asset('images/tribaltours_icon.png') }}" alt="TrblTours" class="brand-icon">
        <div class="brand-text brand-font">TrblTours</div>
    </div>

    <main class="min-h-screen flex items-center justify-center px-4 py-8">
        <section class="container">
            <a href="{{ url('/') }}" class="back-link">
                <i class="fas fa-arrow-left text-[10px]" aria-hidden="true"></i>
                <span>Back to Home</span>
            </a>

            <h1 class="title">Create Your Account</h1>
            <p class="subtitle">Join TrblTours with secure identity verification.</p>

            <div class="role-toggle" data-role-toggle>
                <button type="button" class="role-btn active" data-role-button="tourist">
                    <span class="role-icon"><i class="fas fa-user"></i></span>
                    <span class="text-sm font-medium">Tourist</span>
                </button>

                <button type="button" class="role-btn" data-role-button="tour_guide">
                    <span class="role-icon"><i class="fas fa-map"></i></span>
                    <span class="text-sm font-medium">Tour Guide</span>
                </button>
            </div>

            @if ($errors->any())
                <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="form-grid" data-auth-form>
                @csrf
                <input type="hidden" name="account_type" value="tourist" data-role-input>

                <div>
                    <div class="section-title">Account Information</div>

                    <div class="input-row two">
                        <div class="field">
                            <label for="name" class="required">Full Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
                            @error('name')
                                <p class="text-sm text-rose-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="date_of_birth" class="required">Date of Birth</label>
                            <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                        </div>
                    </div>

                    <div class="input-row two" style="margin-top: 16px;">
                        <div class="field">
                            <label for="password" class="required">Password</label>
                            <div style="position: relative;">
                                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" style="padding-right: 40px;" data-password-toggle-input="password">
                                <button type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: 0; background: transparent; cursor: pointer; font-size: 12px; font-weight: 700; color: #6f5d52;" data-password-toggle="password">Show</button>
                            </div>
                            @error('password')
                                <p class="text-sm text-rose-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="password_confirmation" class="required">Confirm Password</label>
                            <div style="position: relative;">
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" style="padding-right: 40px;" data-password-toggle-input="password_confirmation">
                                <button type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: 0; background: transparent; cursor: pointer; font-size: 12px; font-weight: 700; color: #6f5d52;" data-password-toggle="password_confirmation">Show</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="section-title">Contact Verification</div>

                    <div class="input-row two">
                        <div class="field">
                            <label for="email" class="required">Email Address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="john@example.com">
                            @error('email')
                                <p class="text-sm text-rose-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field self-end">
                            <button type="button" class="secondary-btn">Verify Email</button>
                        </div>
                    </div>

                    <div class="input-row two" style="margin-top: 16px;">
                        <div class="field">
                            <label for="phone_number" class="required">Phone Number</label>
                            <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="+63 999-999-9999">
                        </div>

                        <div class="field self-end">
                            <button type="button" class="secondary-btn">Send OTP</button>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="section-title">Personal Information</div>

                    <div class="field">
                        <label for="nationality" class="required">Nationality</label>
                        <select id="nationality" name="nationality">
                            <option value="Filipino" @selected(old('nationality', 'Filipino') === 'Filipino')>Filipino</option>
                            <option value="Australian" @selected(old('nationality') === 'Australian')>Australian</option>
                            <option value="Canadian" @selected(old('nationality') === 'Canadian')>Canadian</option>
                            <option value="Japanese" @selected(old('nationality') === 'Japanese')>Japanese</option>
                            <option value="American" @selected(old('nationality') === 'American')>American</option>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="section-title">Identity Verification</div>

                    <div class="input-row two">
                        <div class="field">
                            <label for="government_id_type" class="required">Valid ID Type</label>
                            <select id="government_id_type" name="government_id_type">
                                <option value="passport" @selected(old('government_id_type', 'passport') === 'passport')>Passport</option>
                                <option value="national_id" @selected(old('government_id_type') === 'national_id')>National ID</option>
                                <option value="drivers_license" @selected(old('government_id_type') === 'drivers_license')>Driver's License</option>
                                <option value="other" @selected(old('government_id_type') === 'other')>Other</option>
                            </select>
                        </div>

                        <div class="field">
                            <label for="government_id_number">ID Number (Optional)</label>
                            <input id="government_id_number" type="text" name="government_id_number" value="{{ old('government_id_number') }}" placeholder="e.g. AB1234567">
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Register</button>

                <p class="footer-links">
                    Already have an account? <a href="{{ route('login') }}">Log in</a>
                </p>
            </form>
        </section>
    </main>

    <script>
        const authForm = document.querySelector('[data-auth-form]');
        const roleInput = authForm?.querySelector('[data-role-input]');
        const roleButtons = authForm ? Array.from(authForm.querySelectorAll('[data-role-button]')) : [];

        roleButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const selectedRole = button.getAttribute('data-role-button') || 'tourist';

                if (roleInput) {
                    roleInput.value = selectedRole;
                }

                roleButtons.forEach((otherButton) => {
                    otherButton.classList.toggle('active', otherButton === button);
                });
            });
        });
    </script>
</body>
</html>
