<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-gray-800">Daftar Akun Baru</h2>
        <p class="text-sm text-gray-500 mt-2">Buat akun untuk mulai menggunakan Ticketing</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div class="form-control w-full">
            <label class="label" for="name">
                <span class="label-text font-semibold text-gray-700">Nama Lengkap</span>
            </label>
            <div class="relative text-gray-400 focus-within:text-blue-600 transition-colors">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="input input-bordered w-full pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 border-gray-300" placeholder="John Doe">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Email Address -->
        <div class="form-control w-full">
            <label class="label" for="email">
                <span class="label-text font-semibold text-gray-700">Email Address</span>
            </label>
            <div class="relative text-gray-400 focus-within:text-blue-600 transition-colors">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="input input-bordered w-full pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 border-gray-300" placeholder="nama@email.com">
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
                <input id="password" type="password" name="password" required autocomplete="new-password" class="input input-bordered w-full pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 border-gray-300" placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div class="form-control w-full">
            <label class="label" for="password_confirmation">
                <span class="label-text font-semibold text-gray-700">Konfirmasi Password</span>
            </label>
            <div class="relative text-gray-400 focus-within:text-blue-600 transition-colors">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="input input-bordered w-full pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 border-gray-300" placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md hover:shadow-lg transition-all font-semibold tracking-wide">
                Daftar Akun
            </button>
        </div>
        
        <!-- Login Link -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline hover:text-blue-800 transition-colors">Masuk di sini</a></p>
        </div>
    </form>
</x-guest-layout>
