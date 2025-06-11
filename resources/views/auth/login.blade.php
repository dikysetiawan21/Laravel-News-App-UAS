@php use Illuminate\Support\Facades\Route; @endphp
@extends('layouts.guest')

@section('content')
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
        <hr class="my-6">
        <a href="{{ route('auth.google.redirect') }}" class="btn btn-google btn-user btn-block flex items-center justify-center gap-2 mt-2" style="background:#ea4335;color:#fff;font-weight:500;">
            <svg class="inline-block" width="20" height="20" viewBox="0 0 48 48"><g><path fill="#4285F4" d="M24 9.5c3.54 0 6.7 1.22 9.19 3.22l6.87-6.87C36.66 2.7 30.74 0 24 0 14.82 0 6.73 5.34 2.69 13.08l8.01 6.22C12.65 13.53 17.89 9.5 24 9.5z"/><path fill="#34A853" d="M46.1 24.5c0-1.64-.15-3.22-.42-4.74H24v9.02h12.41c-.53 2.86-2.13 5.28-4.52 6.92l7.02 5.47C43.88 36.42 46.1 30.89 46.1 24.5z"/><path fill="#FBBC05" d="M10.7 28.3c-1.08-3.22-1.08-6.68 0-9.9l-8.01-6.22C.77 16.14 0 20.01 0 24c0 3.99.77 7.86 2.69 11.12l8.01-6.22z"/><path fill="#EA4335" d="M24 44c6.74 0 12.66-2.7 17.06-7.38l-7.02-5.47c-2.06 1.39-4.7 2.22-7.54 2.22-6.11 0-11.35-4.03-13.3-9.8l-8.01 6.22C6.73 42.66 14.82 48 24 48z"/><path fill="none" d="M0 0h48v48H0z"/></g></svg>
            Login with Google
        </a>
    </form>
@endsection
