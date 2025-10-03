<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LMS')</title>
    
    <!-- You can add your CSS frameworks here -->
    @stack('styles')
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
    
    <!-- You can add your JavaScript here -->
    @stack('scripts')
</body>
</html>