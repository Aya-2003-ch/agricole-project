<x-guest-layout>
    <style>
        /* تخصيص الألوان لتناسب الهوية الزراعية */
        :root {
            --primary-green: #2d6a4f;
            --secondary-green: #1b4332;
            --soft-bg: #f8f9fa;
        }

        .login-card-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-card-header i {
            font-size: 3rem;
            color: var(--primary-green);
            margin-bottom: 10px;
        }

        .login-card-header h2 {
            color: var(--secondary-green);
            font-weight: 800;
            font-size: 24px;
            margin: 0;
        }

        .custom-input {
            border-radius: 12px !important;
            border: 1.5px solid #e2e8f0 !important;
            padding: 12px !important;
            transition: 0.3s !important;
        }

        .custom-input:focus {
            border-color: var(--primary-green) !important;
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1) !important;
        }

        .btn-agro {
            background-color: var(--primary-green) !important;
            border-radius: 12px !important;
            padding: 12px 0 !important;
            width: 100%;
            justify-content: center;
            font-weight: bold !important;
            text-transform: none !important;
            letter-spacing: normal !important;
            transition: 0.3s !important;
        }

        .btn-agro:hover {
            background-color: var(--secondary-green) !important;
            transform: translateY(-2px);
        }

        .auth-label {
            color: var(--secondary-green) !important;
            font-weight: 600 !important;
            margin-bottom: 5px;
        }
    </style>

    <!-- Header مع أيقونة المنصة -->
    <div class="login-card-header">
        <i class="fas fa-tractor"></i>
        <h2>دخول منصة AgroDz</h2>
        <p style="color: #64748b; font-size: 14px; margin-top: 5px;"> AgroDzمرحباً بك في </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" class="auth-label" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" class="block mt-1 w-full custom-input" 
                          type="email" name="email" :value="old('email')" 
                          required autofocus autocomplete="username" 
                          placeholder="example@mail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" class="auth-label" :value="__('كلمة المرور')" />
            <x-text-input id="password" class="block mt-1 w-full custom-input"
                            type="password"
                            name="password"
                            required autocomplete="current-password" 
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-700 shadow-sm focus:ring-green-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('تذكرني') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-green-700 hover:text-green-900 font-semibold no-underline" href="{{ route('password.request') }}">
                    {{ __('نسيت كلمة المرور؟') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="btn-agro">
                {{ __('تسجيل الدخول') }}
            </x-primary-button>
        </div>

        <!-- رابط لإنشاء حساب جديد -->
        <div class="text-center mt-6">
            <p style="font-size: 14px; color: #64748b;">
                ليس لديك حساب؟ 
                <a href="{{ route('register') }}" style="color: var(--primary-green); font-weight: bold; text-decoration: none;">إنشاء حساب جديد</a>
            </p>
        </div>
    </form>
</x-guest-layout>