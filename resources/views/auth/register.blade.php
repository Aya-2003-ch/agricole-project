<x-guest-layout>

<style>
/* background */
body {
    background: linear-gradient(135deg, #eafaf1, #f4f7f5);
}

/* card */
form {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* labels */
label {
    color: #1e7d4f !important;
    font-weight: bold;
}

/* inputs */
input, select {
    border-radius: 10px !important;
    border: 1px solid #ccc !important;
    padding: 10px !important;
}

/* button */
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

    <!-- Name -->
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Email -->
    <div class="mt-4">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" />
        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm -->
    <div class="mt-4">
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <!-- ROLE  -->
    <div class="mt-4">
        <x-input-label for="role" value="role" />

        <select name="role" id="role" class="block mt-1 w-full" required>
            <option value="">-- اختر  صفتك --</option>
            <option value="ferme"> فلاح</option>
            <option value="veterinaire"> بيطري</option>
            <option value="distributeur"> موزع</option>
        </select>

        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>

    <!-- buttons -->
    <div class="flex items-center justify-between mt-6">

        <a class="underline text-sm text-gray-600 hover:text-green-600"
           href="{{ route('login') }}">
            Already registered?
        </a>

        <x-primary-button>
            {{ __('Register') }}
        </x-primary-button>

    </div>
</form>

</x-guest-layout>