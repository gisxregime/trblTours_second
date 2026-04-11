<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'TrblTours')); ?> - Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asimovian:wght@400;700&family=Cormorant+SC:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        .brand-font {
            font-family: 'Asimovian', 'Instrument Sans', sans-serif;
            letter-spacing: 0.02em;
        }

        body {
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(rgba(212, 165, 99, 0.72), rgba(184, 195, 136, 0.56)), url('/images/signup_login_bg.jpg') center/cover no-repeat fixed;
            color: #6f5d52;
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

        .panel {
            width: 100%;
            max-width: 560px;
            background: rgba(255, 251, 244, 0.98);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(101, 67, 33, 0.22);
            padding: 34px;
            border: 1px solid rgba(139, 69, 19, 0.08);
        }

        .role-toggle {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin: 14px 0 20px;
        }

        .role-btn {
            border: 1.5px solid #d8c9b5;
            border-radius: 12px;
            padding: 14px 12px;
            background: #f7efe1;
            color: #6f5d52;
            text-align: center;
            transition: all 0.2s ease;
        }

        .role-btn.active {
            border-color: #8B4513;
            box-shadow: 0 6px 14px rgba(139, 69, 19, 0.12);
        }
    </style>
</head>
<body>
    <div class="brand-header">
        <img src="<?php echo e(asset('images/tribaltours_icon.png')); ?>" alt="TrblTours" class="brand-icon">
        <div class="brand-text brand-font">TrblTours</div>
    </div>

    <main class="min-h-screen flex items-center justify-center px-4 py-8">
        <section class="panel">
            <a href="<?php echo e(url('/')); ?>" class="inline-flex items-center gap-1 text-[13px] text-[#7b6657] hover:underline">
                <i class="fas fa-arrow-left text-[10px]" aria-hidden="true"></i>
                <span>Back to Home</span>
            </a>

            <div class="mt-3 mb-4">
                <h1 class="text-4xl font-semibold tracking-tight text-[#23170f]">Log in to Your Account</h1>
                <p class="mt-1 text-sm text-[#584637]">Access your TrblTours panel.</p>
            </div>

            <div class="role-toggle">
                <button type="button" id="touristChoice" class="role-btn active">
                    <div class="mx-auto mb-2 flex h-11 w-11 items-center justify-center rounded-xl bg-[#efe4d5] text-[#8b4e1c]">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="text-sm font-medium">Login as a Tourist</span>
                </button>
                <button type="button" id="guideChoice" class="role-btn">
                    <div class="mx-auto mb-2 flex h-11 w-11 items-center justify-center rounded-xl bg-[#efe4d5] text-[#8b4e1c]">
                        <i class="fas fa-map"></i>
                    </div>
                    <span class="text-sm font-medium">Login as a Tour Guide</span>
                </button>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="login_role" value="tourist">

                <p class="border-b border-[#d4c5b2] pb-2 text-[11px] font-semibold uppercase tracking-[0.09em] text-[#604c3a]">Account Information</p>

                <div>
                    <label for="email" class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-[#5c4a3a]">Email Address <span class="text-[#9a4f1d]">*</span></label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="<?php echo e(old('email')); ?>"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="example@email.com"
                        class="w-full rounded-lg border border-[#d8c9b5] bg-[#f4efe6] px-3 py-2.5 text-sm text-[#2f241a] outline-none transition placeholder:text-[#958067] focus:border-[#9a5f2a] focus:ring-0"
                    >
                </div>

                <div>
                    <div class="mb-1 flex items-center justify-between">
                        <label for="password" class="block text-[11px] font-semibold uppercase tracking-wider text-[#5c4a3a]">Password <span class="text-[#9a4f1d]">*</span></label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('password.request')): ?>
                            <a href="<?php echo e(route('password.request')); ?>" class="text-xs text-[#6f5b46] transition hover:text-[#3e3023] hover:underline">Forgot Password?</a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-[#d8c9b5] bg-[#f4efe6] px-3 py-2.5 text-sm text-[#2f241a] outline-none transition focus:border-[#9a5f2a] focus:ring-0"
                    >
                </div>

                <div class="pt-1 text-sm">
                    <label for="remember_me" class="inline-flex items-center gap-2 text-[#6f5b46]">
                        <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-[#c8b49b] text-[#8b4e1c] focus:ring-[#8b4e1c]/30">
                        Remember me
                    </label>
                </div>

                <button
                    type="submit"
                    class="mt-2 w-full rounded-full bg-[#8b4e1c] px-4 py-2.5 text-sm font-semibold text-[#f8efe3] transition hover:bg-[#764116]"
                >
                    Log in
                </button>

                <p class="pt-1 text-center text-sm text-[#6f5b46]">
                    Don't have an account?
                    <a href="<?php echo e(url('/signup.php')); ?>" class="font-medium text-[#4b3828] hover:underline">Sign up</a>
                </p>
            </form>
        </section>
    </main>

    <script>
        const touristChoice = document.getElementById('touristChoice');
        const guideChoice = document.getElementById('guideChoice');
        const loginRole = document.getElementById('login_role');

        touristChoice.addEventListener('click', () => {
            touristChoice.classList.add('active');
            guideChoice.classList.remove('active');
            loginRole.value = 'tourist';
        });

        guideChoice.addEventListener('click', () => {
            guideChoice.classList.add('active');
            touristChoice.classList.remove('active');
            loginRole.value = 'tour_guide';
        });
    </script>
</body>
</html>
<?php /**PATH /home/mistah-regime/tribaltours/resources/views/auth/login.blade.php ENDPATH**/ ?>