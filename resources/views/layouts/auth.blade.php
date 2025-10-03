<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') - {{ config('app.name', 'LMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-gray-50">
    <div class="min-h-full flex">
        <!-- Left Panel - Background Image/Branding -->
        <div class="hidden lg:block relative flex-1">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800">
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="text-center text-white px-8">
                        <div class="mb-8">
                            <h1 class="text-4xl font-bold mb-4">Welcome to {{ config('app.name', 'LMS') }}</h1>
                            <p class="text-xl opacity-90">Your journey to knowledge starts here</p>
                        </div>
                        <div class="grid grid-cols-1 gap-6 max-w-md mx-auto">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                        <span class="material-icons text-white">school</span>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <h3 class="font-semibold">Quality Education</h3>
                                    <p class="text-sm opacity-80">Learn from industry experts</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                        <span class="material-icons text-white">devices</span>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <h3 class="font-semibold">Flexible Learning</h3>
                                    <p class="text-sm opacity-80">Study at your own pace</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                        <span class="material-icons text-white">verified</span>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <h3 class="font-semibold">Certificates</h3>
                                    <p class="text-sm opacity-80">Get recognized credentials</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Auth Form -->
        <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-md lg:w-96">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>