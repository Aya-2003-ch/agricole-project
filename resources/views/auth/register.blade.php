<x-guest-layout>

<style>
body {
    background: linear-gradient(135deg, #eafaf1, #f4f7f5);
}

form {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

label {
    color: #1e7d4f !important;
    font-weight: bold;
}

input, select {
    border-radius: 10px !important;
    border: 1px solid #ccc !important;
    padding: 10px !important;
}

button {
    background: linear-gradient(90deg, #27ae60, #1e7d4f) !important;
    border-radius: 30px !important;
    padding: 10px 20px !important;
    transition: 0.3s;
}

button:hover {
    transform: scale(1.05);
}
</style>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- NAME -->
    <div>
        <x-input-label for="name" value="الاسم" />
        <x-text-input id="name" class="block mt-1 w-full"
            type="text" name="name"
            :value="old('name')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- EMAIL -->
    <div class="mt-4">
        <x-input-label for="email" value="البريد الإلكتروني" />
        <x-text-input id="email" class="block mt-1 w-full"
            type="email" name="email"
            :value="old('email')" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- PHONE -->
    <div class="mt-4">
        <x-input-label for="telephone" value="رقم الهاتف" />
        <x-text-input id="telephone" class="block mt-1 w-full"
            type="text" name="telephone"
            :value="old('telephone')" required />
        <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
    </div>

    <!-- ADDRESS -->
    <div class="mt-4">
        <x-input-label for="address" value="العنوان" />
        <x-text-input id="address" class="block mt-1 w-full"
            type="text" name="address"
            :value="old('address')" required />
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <!-- ROLE -->
    <div class="mt-4">
        <x-input-label for="role" value="نوع الحساب" />

        <select name="role" id="role" class="block mt-1 w-full" required>
            <option value="">-- اختر نوع الحساب --</option>

            <option value="eleveur"
                {{ old('role') == 'eleveur' ? 'selected' : '' }}>
                🐄 فلاح (Éleveur)
            </option>

            <option value="veterinaire"
                {{ old('role') == 'veterinaire' ? 'selected' : '' }}>
                🩺 بيطري (Vétérinaire)
            </option>

            <option value="distributeur"
                {{ old('role') == 'distributeur' ? 'selected' : '' }}>
                🚚 موزع (Distributeur)
            </option>
        </select>

        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>

    <!-- PASSWORD -->
    <div class="mt-4">
        <x-input-label for="password" value="كلمة المرور" />
        <x-text-input id="password" class="block mt-1 w-full"
            type="password" name="password" required />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- CONFIRM PASSWORD -->
    <div class="mt-4">
        <x-input-label for="password_confirmation" value="تأكيد كلمة المرور" />
        <x-text-input id="password_confirmation" class="block mt-1 w-full"
            type="password" name="password_confirmation" required />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <!-- BUTTONS -->
    <div class="flex items-center justify-between mt-6">

        <a class="underline text-sm text-gray-600 hover:text-green-600"
           href="{{ route('login') }}">
            عندك حساب؟ تسجيل الدخول
        </a>

        <x-primary-button>
            تسجيل
        </x-primary-button>

    </div>
</form>

</x-guest-layout>