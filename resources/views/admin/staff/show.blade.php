@extends('layouts.admin')

@section('title', $staff->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $staff->name }}</h1>
            <p class="mt-1 text-sm text-gray-500">Staff member details and information</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.staff.edit', $staff) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons text-sm mr-2">edit</span>
                Edit
            </a>
            <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to Staff
            </a>
        </div>
    </div>

    <!-- Staff Information -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-20 w-20">
                    <div class="h-20 w-20 rounded-full bg-red-500 flex items-center justify-center">
                        <span class="text-2xl font-medium text-white">
                            {{ strtoupper(substr($staff->name, 0, 1)) }}
                        </span>
                    </div>
                </div>
                <div class="ml-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $staff->name }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $staff->email }}</p>
                    <span class="mt-2 inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium {{ $staff->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucfirst($staff->role) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Full name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $staff->name }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Email address</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $staff->email }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ ucfirst($staff->role) }}
                        @if($staff->role === 'admin')
                            <span class="text-xs text-gray-500">- Full system access</span>
                        @else
                            <span class="text-xs text-gray-500">- Course and student management</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Member since</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $staff->created_at->format('F j, Y') }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Last updated</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $staff->updated_at->format('F j, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Actions</h3>
            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <a href="{{ route('admin.staff.edit', $staff) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    <span class="material-icons text-sm mr-2">edit</span>
                    Edit Staff Member
                </a>
                @if($staff->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.staff.destroy', $staff) }}" class="inline" 
                          onsubmit="return confirm('Are you sure you want to delete this staff member? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <span class="material-icons text-sm mr-2">delete</span>
                            Delete Staff Member
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection