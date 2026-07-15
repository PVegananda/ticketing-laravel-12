<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-gray-800">Selamat Datang</h2>
        <p class="text-sm text-gray-500 mt-2">Silakan masuk untuk melanjutkan ke Dashboard</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div class="form-control w-full">
            <label class="label" for="email">
                <span class="label-text font-semibold text-gray-700">Email Address</span>
            </label>
            <div class="relative text-gray-400 focus-within:text-blue-600 transition-colors">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="input input-bordered w-full pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 border-gray-300" placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Password -->
        <div class="form-control w-full">
            <label class="label" for="password">
                <span class="label-text font-semibold text-gray-700">Password</span>
            </label>
            <div class="relative text-gray-400 focus-within:text-blue-600 transition-colors">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="input input-bordered w-full pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 border-gray-300" placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label class="flex items-center cursor-pointer gap-2 hover:bg-gray-50 p-1 rounded transition-colors">
                <input id="remember_me" type="checkbox" name="remember" class="checkbox checkbox-sm checkbox-primary border-gray-300">
                <span class="text-sm font-medium text-gray-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline transition-colors" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="btn btn-primary w-full text-white shadow-md hover:shadow-lg transition-all text-base font-semibold tracking-wide">
                Log In
            </button>
        </div>
        
        <!-- Register Link -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline hover:text-blue-800 transition-colors">Daftar sekarang</a></p>
        </div>
    </form>
</x-guest-layout>
