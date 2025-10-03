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
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
                 style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80');">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/70 via-blue-800/70 to-indigo-900/70">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="text-center text-white px-8">
                        <div class="mb-8">
                            <h1 class="text-4xl font-bold mb-4">Welcome to {{ config('app.name', 'LMS') }}</h1>
                            <p class="text-xl opacity-90">Your journey to knowledge starts here</p>
                        </div>
                        <div class="grid grid-cols-1 gap-6 max-w-md mx-auto">
                            <!-- Quality Education Feature -->
                            <div class="group flex items-center space-x-4 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                        <span class="material-icons text-white text-xl">school</span>
                                    </div>
                                </div>
                                <div class="text-left flex-1">
                                    <h3 class="font-bold text-lg text-white group-hover:text-blue-100 transition-colors duration-300">Quality Education</h3>
                                    <p class="text-sm text-blue-100 opacity-90 mt-1">Learn from industry experts with real-world experience</p>
                                    <div class="flex items-center mt-2">
                                        <div class="flex space-x-1">
                                            <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                                            <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                                            <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                                            <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                                            <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                                        </div>
                                        <span class="text-xs text-blue-100 ml-2">5.0 Rating</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Flexible Learning Feature -->
                            <div class="group flex items-center space-x-4 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                        <span class="material-icons text-white text-xl">schedule</span>
                                    </div>
                                </div>
                                <div class="text-left flex-1">
                                    <h3 class="font-bold text-lg text-white group-hover:text-green-100 transition-colors duration-300">Flexible Learning</h3>
                                    <p class="text-sm text-blue-100 opacity-90 mt-1">Study at your own pace, anytime, anywhere</p>
                                    <div class="flex items-center mt-2">
                                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                        <span class="text-xs text-blue-100 ml-2">24/7 Access</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Certificates Feature -->
                            <div class="group flex items-center space-x-4 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                        <span class="material-icons text-white text-xl">verified</span>
                                    </div>
                                </div>
                                <div class="text-left flex-1">
                                    <h3 class="font-bold text-lg text-white group-hover:text-purple-100 transition-colors duration-300">Certificates</h3>
                                    <p class="text-sm text-blue-100 opacity-90 mt-1">Get recognized credentials upon completion</p>
                                    <div class="flex items-center mt-2">
                                        <span class="text-xs bg-purple-500/30 text-purple-100 px-2 py-1 rounded-full">Verified</span>
                                        <span class="text-xs text-blue-100 ml-2">Industry Standard</span>
                                    </div>
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