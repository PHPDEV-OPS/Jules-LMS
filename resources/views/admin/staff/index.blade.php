@extends('layouts.admin')

@section('title', 'Staff Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Staff Management</h1>
            <p class="mt-1 text-sm text-gray-500">Manage administrators and tutors in your system</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons text-sm mr-2">add</span>
                Add Staff Member
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-gray-400">people</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Staff</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_staff'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-gray-400">admin_panel_settings</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Administrators</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_admins'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-gray-400">school</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Tutors</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_tutors'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-gray-400">trending_up</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Recent Additions</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['recent_additions'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('admin.staff.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Search by name or email..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="tutor" {{ request('role') === 'tutor' ? 'selected' : '' }}>Tutor</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    <span class="material-icons text-sm mr-2">search</span>
                    Filter
                </button>
                <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Staff Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($staff as $member)
                <li>
                    <div class="px-4 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-red-500 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $member->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($member->role) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                <div class="text-xs text-gray-400">
                                    Joined {{ $member->created_at->format('M j, Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.staff.show', $member) }}" class="text-gray-400 hover:text-gray-600">
                                <span class="material-icons text-sm">visibility</span>
                            </a>
                            <a href="{{ route('admin.staff.edit', $member) }}" class="text-gray-400 hover:text-gray-600">
                                <span class="material-icons text-sm">edit</span>
                            </a>
                            @if($member->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.staff.destroy', $member) }}" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this staff member?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600">
                                        <span class="material-icons text-sm">delete</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center">
                    <span class="material-icons text-4xl text-gray-300">people</span>
                    <p class="mt-2 text-sm text-gray-500">No staff members found.</p>
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Pagination -->
    @if($staff->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            {{ $staff->links() }}
        </div>
    @endif
</div>
@endsection