@extends('layouts.auth')

@section('title', 'Admin Sign In')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="text-center">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900">Admin Sign In</h2>
        <p class="mt-2 text-gray-600">Access the administrative dashboard</p>
    </div>

    <!-- Admin Sign In Form -->
    <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
        @csrf
        
        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Admin Email
            </label>
            <div class="relative">
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    autocomplete="email" 
                    required 
                    placeholder="Enter your admin email"
                    value="{{ old('email') }}"
                    class="block w-full rounded-lg border px-4 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200 {{ $errors->has('email') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-red-500 focus:ring-red-500' }}"
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
                    placeholder="Enter your password"
                    class="block w-full rounded-lg border px-4 py-3 pr-12 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200 {{ $errors->has('password') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-red-500 focus:ring-red-500' }}"
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

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input 
                    id="remember" 
                    name="remember" 
                    type="checkbox" 
                    class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Remember me
                </label>
            </div>
        </div>

        <!-- Sign In Button -->
        <button 
            type="submit" 
            class="w-full rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center space-x-2"
        >
            <span class="material-icons mr-2">admin_panel_settings</span>
            <span>ADMIN SIGN IN</span>
        </button>
    </form>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="bg-gray-50 px-2 text-gray-500">Security Notice</span>
        </div>
    </div>

    <!-- Security Notice -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <span class="material-icons text-yellow-400">security</span>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">
                    Administrative Access Only
                </h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>This area is restricted to administrators and authorized tutors only. All access attempts are logged and monitored for security purposes.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Login Link -->
    <div class="text-center">
        <p class="text-sm text-gray-600">
            Not an admin? 
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Student Sign In
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