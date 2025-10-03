<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'LMS') }}</title>

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
        .sidebar-active {
            transform: translateX(0);
        }
        .sidebar-inactive {
            transform: translateX(-100%);
        }
        
        /* Ensure sidebar is always visible on desktop */
        @media (min-width: 1024px) {
            .sidebar-inactive {
                transform: translateX(0) !important;
            }
            .sidebar-active {
                transform: translateX(0) !important;
            }
        }
        
        /* Ensure proper layout flow */
        @media (min-width: 1024px) {
            .lg\:ml-64 {
                margin-left: 16rem !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:transform-none sidebar-inactive lg:sidebar-active flex flex-col" id="sidebar">
            <div class="flex items-center justify-center h-16 px-4 bg-red-600 flex-shrink-0">
                <h1 class="text-xl font-bold text-white">{{ config('app.name', 'LMS') }} Admin</h1>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 mt-5 px-2 overflow-y-auto">
                <div class="space-y-1 pb-6">
                    <!-- Dashboard -->
                    <div class="mb-4">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Dashboard</p>
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">dashboard</span>
                            Overview
                        </a>
                        <a href="{{ route('admin.analytics') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.analytics') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">analytics</span>
                            Analytics
                        </a>
                        <a href="{{ route('admin.reports') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">assessment</span>
                            Reports
                        </a>
                    </div>

                    <!-- Management -->
                    <div class="mb-4">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Management</p>
                        <a href="{{ route('students.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                            <span class="material-icons mr-3 text-sm">people</span>
                            Students
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.courses.*') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">school</span>
                            Courses
                        </a>
                        <a href="{{ route('enrollments.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                            <span class="material-icons mr-3 text-sm">assignment</span>
                            Enrollments
                        </a>
                        <a href="{{ route('admin.staff.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.staff.*') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">admin_panel_settings</span>
                            Staff
                        </a>
                    </div>

                    <!-- Academic -->
                    <div class="mb-4">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Academic</p>
                        <a href="{{ route('admin.categories.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.categories.*') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">category</span>
                            Categories
                        </a>
                        <a href="{{ route('admin.assessments.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.assessments.*') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">quiz</span>
                            Assessments
                        </a>
                        <a href="{{ route('admin.certificates.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.certificates.*') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">verified</span>
                            Certificates
                        </a>
                        <a href="{{ route('admin.gradings.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.gradings.*') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">grade</span>
                            Grading
                        </a>
                    </div>

                    <!-- Communication -->
                    <div class="mb-4">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Communication</p>
                        <a href="{{ route('admin.announcements.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.announcements.*') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">campaign</span>
                            Announcements
                        </a>
                        <a href="{{ route('admin.email-templates.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.email-templates.*') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">email</span>
                            Email Templates
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.notifications.*') ? 'bg-red-50 text-red-700 border-r-2 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">notifications</span>
                            Notifications
                        </a>
                        <a href="{{ route('admin.forums.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.forums.*') ? 'bg-purple-50 text-purple-700 border-r-2 border-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">forum</span>
                            Forums
                        </a>
                    </div>

                    <!-- System -->
                    <div class="mb-4">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">System</p>
                        <a href="{{ route('admin.system.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.system.settings*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">settings</span>
                            System Settings
                        </a>
                        <a href="{{ route('admin.system.backup') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.system.backup*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">backup</span>
                            Backup & Restore
                        </a>
                        <a href="{{ route('admin.system.activity-logs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.system.activity-logs*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">history</span>
                            Activity Logs
                        </a>
                        <a href="{{ route('admin.system.help') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.system.help*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-icons mr-3 text-sm">help</span>
                            Help & Support
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Admin Profile at Bottom -->
            <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-gray-500 hover:text-gray-700 flex items-center mt-1">
                                <span class="material-icons text-xs mr-1">logout</span>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar overlay -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden hidden" id="sidebar-overlay"></div>

        <!-- Main content -->
        <div class="flex-1 lg:ml-64">
            <!-- Top navigation -->
            <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <!-- Mobile menu button -->
                <button type="button" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-500 lg:hidden" id="sidebar-toggle">
                    <span class="sr-only">Open sidebar</span>
                    <span class="material-icons">menu</span>
                </button>
                
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex">
                        <form class="w-full flex md:ml-0" action="#" method="GET">
                            <label for="search-field" class="sr-only">Search</label>
                            <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                    <span class="material-icons">search</span>
                                </div>
                                <input id="search-field" class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent" placeholder="Search students, courses, enrollments..." type="search" name="search">
                            </div>
                        </form>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- Quick Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('students.create') }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200">
                                <span class="material-icons text-xs mr-1">person_add</span>
                                Add Student
                            </a>
                            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200">
                                <span class="material-icons text-xs mr-1">add</span>
                                Add Course
                            </a>
                        </div>

                        <!-- Notifications -->
                        <button type="button" class="ml-4 bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <span class="sr-only">View notifications</span>
                            <span class="material-icons">notifications</span>
                        </button>

                        <!-- Profile dropdown -->
                        <div class="ml-3 relative">
                            <div class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content area -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        <!-- Success/Error Messages -->
                        @if(session('success'))
                            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                                <div class="flex">
                                    <span class="material-icons text-green-400 mr-2">check_circle</span>
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                                <div class="flex">
                                    <span class="material-icons text-red-400 mr-2">error</span>
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('sidebar-active');
            sidebar.classList.toggle('sidebar-inactive');
            sidebarOverlay.classList.toggle('hidden');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }

        // Close sidebar on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (sidebarOverlay && !sidebarOverlay.classList.contains('hidden')) {
                    toggleSidebar();
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>