<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Learning Management System') }} - Master New Skills Online</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .course-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .feature-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-18">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="#home" class="text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">
                            LMS Academy
                        </a>
                    </div>
                    <div class="hidden md:ml-10 md:flex md:space-x-10">
                        <a href="#about" class="text-gray-900 hover:text-blue-600 px-4 py-3 text-sm font-medium transition-colors duration-200 border-b-2 border-transparent hover:border-blue-600">About</a>
                        <a href="#courses" class="text-gray-600 hover:text-blue-600 px-4 py-3 text-sm font-medium transition-colors duration-200 border-b-2 border-transparent hover:border-blue-600">Courses</a>
                        <a href="#features" class="text-gray-600 hover:text-blue-600 px-4 py-3 text-sm font-medium transition-colors duration-200 border-b-2 border-transparent hover:border-blue-600">Features</a>
                        <a href="#testimonials" class="text-gray-600 hover:text-blue-600 px-4 py-3 text-sm font-medium transition-colors duration-200 border-b-2 border-transparent hover:border-blue-600">Reviews</a>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    @if(Route::has('login'))
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 text-sm font-medium">Login</a>
                    @endif
                    @if(Route::has('student.register'))
                        <a href="{{ route('student.register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Get Started</a>
                    @else
                        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Get Started</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative pt-16 pb-20 overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-60" 
             style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80');">
        </div>
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/60 to-purple-700/60"></div>
        
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
            <div class="absolute top-1/3 left-1/4 w-32 h-32 bg-yellow-300/20 rounded-full blur-2xl animate-pulse delay-500"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left">
                    <h1 class="text-4xl font-bold text-white sm:text-5xl lg:text-6xl">
                        Learn new skills with 
                        <span class="text-yellow-300">Expert Instructors</span>
                    </h1>
                    <p class="mt-3 text-base text-blue-100 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl">
                        Master in-demand skills with our comprehensive online learning platform. Join thousands of students already advancing their careers.
                    </p>
                    <div class="mt-8 sm:max-w-lg sm:mx-auto sm:text-center lg:text-left lg:mx-0">
                        <div class="flex flex-col sm:flex-row gap-4">
                            @if(Route::has('student.register'))
                                <a href="{{ route('student.register') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 transition-colors">
                                    <i class="material-icons mr-2">school</i>
                                    Start Learning Today
                                </a>
                            @else
                                <a href="#" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 transition-colors">
                                    <i class="material-icons mr-2">school</i>
                                    Start Learning Today
                                </a>
                            @endif
                            <a href="#courses" class="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-blue-600 transition-colors">
                                <i class="material-icons mr-2">explore</i>
                                Explore Courses
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mt-12 relative sm:max-w-lg sm:mx-auto lg:mt-0 lg:max-w-none lg:mx-0 lg:col-span-6 lg:flex lg:items-center">
                    <div class="relative mx-auto w-full rounded-lg shadow-lg lg:max-w-md">
                        <div class="bg-white rounded-lg p-6">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                                    <i class="material-icons text-white">person</i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Expert Instructor</h3>
                                    <p class="text-gray-500">Professional with 10+ years experience</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        <i class="material-icons text-sm">star</i>
                                        <i class="material-icons text-sm">star</i>
                                        <i class="material-icons text-sm">star</i>
                                        <i class="material-icons text-sm">star</i>
                                        <i class="material-icons text-sm">star</i>
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">4.9/5 ({{ $statistics['certificates_issued'] }} certificates)</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="material-icons text-sm mr-2">groups</i>
                                    <span class="text-sm">{{ number_format($statistics['total_students']) }}+ Students</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="material-icons text-sm mr-2">play_circle</i>
                                    <span class="text-sm">{{ $statistics['total_courses'] }}+ Courses</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl mb-6">
                        Empowering Learners Worldwide
                    </h2>
                    <div class="space-y-4 text-lg text-gray-600">
                        <p>
                            At LMS Academy, we believe that education should be accessible, engaging, and transformative. 
                            Our platform connects passionate learners with industry experts to create meaningful learning experiences.
                        </p>
                        <p>
                            With over <strong>{{ $statistics['total_courses'] }} courses</strong> across multiple disciplines and 
                            <strong>{{ number_format($statistics['total_students']) }}+ active students</strong>, we've built a thriving 
                            community of lifelong learners who are advancing their careers and pursuing their passions.
                        </p>
                        <p>
                            Our mission is simple: to democratize quality education and make skill development accessible 
                            to everyone, everywhere. Whether you're looking to advance in your current career, switch fields, 
                            or explore new interests, we're here to support your learning journey.
                        </p>
                    </div>
                    <div class="mt-8 grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">{{ $statistics['total_courses'] }}+</div>
                            <div class="text-sm text-gray-500">Expert-Led Courses</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ number_format($statistics['total_students']) }}+</div>
                            <div class="text-sm text-gray-500">Active Students</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">{{ $statistics['certificates_issued'] }}+</div>
                            <div class="text-sm text-gray-500">Certificates Issued</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-red-600">95%</div>
                            <div class="text-sm text-gray-500">Completion Rate</div>
                        </div>
                    </div>
                </div>
                <div class="mt-12 lg:mt-0">
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-8">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="material-icons text-white">school</i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Expert Instructors</h3>
                                    <p class="text-gray-600">Learn from industry professionals with years of real-world experience.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                        <i class="material-icons text-white">schedule</i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Flexible Learning</h3>
                                    <p class="text-gray-600">Study at your own pace with 24/7 access to all course materials.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                                        <i class="material-icons text-white">support</i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Community Support</h3>
                                    <p class="text-gray-600">Connect with fellow learners and get help when you need it.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses Section -->
    <section id="courses" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Featured Courses</h2>
                <p class="mt-4 text-xl text-gray-600">Learn from industry experts and advance your career</p>
            </div>
            
            <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($featuredCourses as $course)
                    @php
                        $colorClasses = [
                            'bg' => ['bg-gradient-to-br from-blue-400 to-blue-600', 'bg-gradient-to-br from-green-400 to-green-600', 'bg-gradient-to-br from-purple-400 to-purple-600', 'bg-gradient-to-br from-red-400 to-red-600', 'bg-gradient-to-br from-yellow-400 to-yellow-600', 'bg-gradient-to-br from-indigo-400 to-indigo-600'],
                            'badge' => ['bg-blue-100 text-blue-800', 'bg-green-100 text-green-800', 'bg-purple-100 text-purple-800', 'bg-red-100 text-red-800', 'bg-yellow-100 text-yellow-800', 'bg-indigo-100 text-indigo-800']
                        ];
                        $icons = ['code', 'analytics', 'palette', 'campaign', 'business', 'security'];
                        $colorIndex = $loop->index % count($colorClasses['bg']);
                        $bgClass = $colorClasses['bg'][$colorIndex];
                        $badgeClass = $colorClasses['badge'][$colorIndex];
                        $icon = $icons[$colorIndex];
                        
                        $rating = 4.5 + (rand(0, 4) / 10); // Random rating between 4.5-4.9
                    @endphp
                    
                    <div class="course-card bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="h-48 {{ $bgClass }} flex items-center justify-center">
                            @if($course->image_url)
                                <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <i class="material-icons text-white text-6xl">{{ $icon }}</i>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                @if($course->category)
                                    <span class="{{ $badgeClass }} text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $course->category->name }}
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">General</span>
                                @endif
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400 text-sm">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="material-icons text-sm">star</i>
                                        @endfor
                                    </div>
                                    <span class="ml-1 text-sm text-gray-600">{{ number_format($rating, 1) }}</span>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $course->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($course->description, 100) }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-500 text-sm">
                                    <i class="material-icons text-sm mr-1">schedule</i>
                                    <span>{{ $course->duration ?? '8 weeks' }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($course->price > 0)
                                        <span class="text-lg font-bold text-gray-900">${{ number_format($course->price, 0) }}</span>
                                    @endif
                                    @if(Route::has('student.register'))
                                        <a href="{{ route('student.register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                            Enroll Now
                                        </a>
                                    @else
                                        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                            Enroll Now
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-3 flex items-center text-sm text-gray-500">
                                <i class="material-icons text-sm mr-1">people</i>
                                <span>{{ $course->enrollments_count }} enrolled</span>
                                @if($course->instructor)
                                    <span class="ml-4">by {{ $course->instructor }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="material-icons text-gray-400 text-6xl mb-4">school</i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Courses Available</h3>
                        <p class="text-gray-600">Check back later for new courses!</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                @if(Route::has('student.register'))
                    <a href="{{ route('student.register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                        View All Courses
                        <i class="material-icons ml-2">arrow_forward</i>
                    </a>
                @else
                    <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                        View All Courses
                        <i class="material-icons ml-2">arrow_forward</i>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Why Choose Our Platform?</h2>
                <p class="mt-4 text-xl text-gray-600">Everything you need for successful online learning</p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="text-center">
                    <div class="feature-icon w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                        <i class="material-icons text-white text-2xl">verified</i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Certificate of Completion</h3>
                    <p class="text-gray-600">Get recognized certificates upon successful course completion to boost your career.</p>
                </div>

                <div class="text-center">
                    <div class="feature-icon w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                        <i class="material-icons text-white text-2xl">devices</i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Access on All Devices</h3>
                    <p class="text-gray-600">Learn anywhere, anytime on your desktop, tablet, or mobile device.</p>
                </div>

                <div class="text-center">
                    <div class="feature-icon w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                        <i class="material-icons text-white text-2xl">download</i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Downloadable Resources</h3>
                    <p class="text-gray-600">Access course materials, assignments, and resources offline anytime.</p>
                </div>

                <div class="text-center">
                    <div class="feature-icon w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                        <i class="material-icons text-white text-2xl">money_off</i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Money-Back Guarantee</h3>
                    <p class="text-gray-600">100% satisfaction guaranteed or get your money back within 30 days.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">What Our Students Say</h2>
                <p class="mt-4 text-xl text-gray-600">Join thousands of successful learners</p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-8 lg:grid-cols-3">
                @foreach($testimonials as $testimonial)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex text-yellow-400 mb-4">
                        @for($i = 0; $i < $testimonial['rating']; $i++)
                            <i class="material-icons">star</i>
                        @endfor
                    </div>
                    <p class="text-gray-600 mb-4">"{{ $testimonial['comment'] }}"</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $testimonial['color'] }} rounded-full flex items-center justify-center text-white font-semibold">
                            {{ $testimonial['initials'] }}
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-semibold text-gray-900">{{ $testimonial['name'] }}</h4>
                            <p class="text-sm text-gray-500">{{ $testimonial['role'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="hero-gradient py-20">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-white sm:text-4xl">
                Start learning new skills today, with the best possible guidance
            </h2>
            <p class="mt-4 text-xl text-blue-100">
                Join our community of learners and take your career to the next level
            </p>
            <div class="mt-8">
                @if(Route::has('student.register'))
                    <a href="{{ route('student.register') }}" class="inline-flex items-center px-8 py-4 border-2 border-white text-lg font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 transition-colors">
                        <i class="material-icons mr-2">school</i>
                        Enroll Today
                    </a>
                @else
                    <a href="#" class="inline-flex items-center px-8 py-4 border-2 border-white text-lg font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 transition-colors">
                        <i class="material-icons mr-2">school</i>
                        Enroll Today
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-xl font-bold mb-3">LMS Academy</h3>
                    <p class="text-gray-400 text-sm mb-4">Democratizing quality education through accessible online learning. Join {{ number_format($statistics['total_students']) }}+ students advancing their careers.</p>
                    <div class="text-sm text-gray-400">
                        <p>{{ $statistics['total_courses'] }}+ Courses Available</p>
                        <p>{{ $statistics['certificates_issued'] }}+ Certificates Issued</p>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-3">Popular Categories</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        @forelse($categories->take(5) as $category)
                            <li><a href="#courses" class="hover:text-white transition-colors">{{ $category->name }}</a></li>
                        @empty
                            <li><a href="#courses" class="hover:text-white transition-colors">Cooking</a></li>
                            <li><a href="#courses" class="hover:text-white transition-colors">Grilling</a></li>
                            <li><a href="#courses" class="hover:text-white transition-colors">Baking</a></li>
                        @endforelse
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-3">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#about" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#courses" class="hover:text-white transition-colors">Browse Courses</a></li>
                        <li><a href="#contact" class="hover:text-white transition-colors">Contact Support</a></li>
                        @if(Route::has('login'))
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Student Login</a></li>
                        @endif
                        @if(Route::has('student.register'))
                            <li><a href="{{ route('student.register') }}" class="hover:text-white transition-colors">Get Started</a></li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} LMS Academy. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 sm:mt-0">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Smooth scroll script -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Mobile menu toggle (if needed)
        // Add mobile menu functionality here if required
    </script>
</body>
</html>