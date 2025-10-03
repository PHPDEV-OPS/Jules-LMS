@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="text-center">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900">Sign In</h2>
        <p class="mt-2 text-gray-600">Enter your email and password to Sign In.</p>
    </div>

    <!-- Sign In Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf
        
        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Your email
            </label>
            <div class="relative">
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    autocomplete="email" 
                    required 
                    placeholder="Your email"
                    value="{{ old('email') }}"
                    class="block w-full rounded-lg border px-4 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200 {{ $errors->has('email') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500' }}"
                >
                @error('email')
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <span class="material-icons text-red-500 text-xl">error</span>
                    </div>
                @enderror
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Password
            </label>
            <div class="relative">
                <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    autocomplete="current-password" 
                    required 
                    placeholder="Password"
                    class="block w-full rounded-lg border px-4 py-3 pr-12 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200 {{ $errors->has('password') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500' }}"
                >
                <button 
                    type="button" 
                    onclick="togglePassword('password')"
                    class="absolute inset-y-0 right-0 flex items-center pr-3"
                >
                    <span class="material-icons text-gray-400 hover:text-gray-600 transition-colors" id="password-toggle-icon">visibility</span>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me and Terms -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input 
                    id="remember" 
                    name="remember" 
                    type="checkbox" 
                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    I agree the <a href="#" class="text-blue-600 hover:text-blue-500 underline">Terms and Conditions</a>
                </label>
            </div>
        </div>

        <!-- Newsletter Subscription -->
        <div class="flex items-center">
            <input 
                id="newsletter" 
                name="newsletter" 
                type="checkbox" 
                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            >
            <label for="newsletter" class="ml-2 block text-sm text-gray-700">
                Subscribe me to newsletter
            </label>
        </div>

        <!-- Sign In Button -->
        <button 
            type="submit" 
            class="w-full rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center space-x-2"
        >
            <span>SIGN IN</span>
        </button>

        <!-- Forgot Password Link -->
        <div class="text-center">
            <a href="#" class="text-sm text-blue-600 hover:text-blue-500 underline">
                Forgot Password
            </a>
        </div>
    </form>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="bg-gray-50 px-2 text-gray-500">Or continue with</span>
        </div>
    </div>

    <!-- Social Login Buttons -->
    <div class="space-y-3">
        <!-- Google Sign In -->
        <button 
            type="button"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center space-x-3"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span>SIGN IN WITH GOOGLE</span>
        </button>

        <!-- Twitter Sign In -->
        <button 
            type="button"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center space-x-3"
        >
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
            </svg>
            <span>SIGN IN WITH TWITTER</span>
        </button>
    </div>

    <!-- Sign Up Link -->
    <div class="text-center">
        <p class="text-sm text-gray-600">
            Not registered? 
            <a href="{{ route('student.register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Create account
            </a>
        </p>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-toggle-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        field.type = 'password';
        icon.textContent = 'visibility';
    }
}
</script>
@endpush
@endsection