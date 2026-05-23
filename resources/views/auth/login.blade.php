@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="w-full max-w-md mx-auto px-4">
    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        {{-- Top Banner --}}
        <div class="bg-primary px-8 py-8 text-center">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow">
                <svg class="w-9 h-9 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
            </div>
            <h1 class="text-white font-bold text-xl">Barangay San Jose</h1>
            <p class="text-blue-200 text-sm mt-1">Integrated Resident Information System</p>
        </div>

        {{-- Form --}}
        <div class="px-8 py-8">
            <h2 class="text-gray-800 font-semibold text-lg mb-6">Sign in to your account</h2>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-5">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-400 @enderror"
                        placeholder="Enter your email"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10 @error('password') border-red-400 @enderror"
                            placeholder="Enter your password"
                        >
                        <button type="button" id="togglePw" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-blue-900 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors duration-150 text-sm">
                    Sign In
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100">
                <p class="text-xs text-center text-gray-500">Public document verification?</p>
                <a href="{{ route('verify.index') }}" class="block text-center text-sm text-blue-600 hover:text-blue-800 font-medium mt-1">
                    Verify Document →
                </a>
            </div>
        </div>
    </div>

    <p class="text-center text-xs text-gray-400 mt-5">
        © {{ date('Y') }} Barangay San Jose · All rights reserved
    </p>
</div>

<script>
    document.getElementById('togglePw').addEventListener('click', function() {
        const pw = document.getElementById('password');
        pw.type = pw.type === 'password' ? 'text' : 'password';
    });
</script>
@endsection
