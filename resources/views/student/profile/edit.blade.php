@extends('layouts.dashboard')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
            <p class="mt-1 text-sm text-gray-500">Update your personal information and preferences</p>
        </div>
        <a href="{{ route('student.profile.show') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <span class="material-icons mr-2 text-sm">arrow_back</span>
            Back to Profile
        </a>
    </div>

    <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Avatar Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Profile Photo</h2>
            </div>
            <div class="px-6 py-6">
                <div class="flex items-center space-x-6">
                    <div class="shrink-0">
                        @if($student->avatar)
                            <img class="h-20 w-20 object-cover rounded-full" 
                                 src="{{ Storage::url($student->avatar) }}" 
                                 alt="Current avatar" 
                                 id="avatar-preview">
                        @else
                            <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center" id="avatar-preview">
                                <span class="text-gray-500 text-xl font-medium">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                            Change avatar
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="file" 
                                   name="avatar" 
                                   id="avatar" 
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @if($student->avatar)
                            <button type="button" 
                                    onclick="deleteAvatar()" 
                                    class="inline-flex items-center px-3 py-1 border border-red-300 text-sm font-medium rounded text-red-700 bg-red-50 hover:bg-red-100">
                                <span class="material-icons mr-1 text-sm">delete</span>
                                Remove
                            </button>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
            </div>
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">
                            First Name *
                        </label>
                        <input type="text" 
                               name="first_name" 
                               id="first_name" 
                               value="{{ old('first_name', $student->first_name) }}"
                               required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">
                            Last Name *
                        </label>
                        <input type="text" 
                               name="last_name" 
                               id="last_name" 
                               value="{{ old('last_name', $student->last_name) }}"
                               required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email Address *
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $student->email) }}"
                               required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Phone Number
                        </label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone', $student->phone) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="sm:col-span-2">
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">
                            Date of Birth
                        </label>
                        <input type="date" 
                               name="date_of_birth" 
                               id="date_of_birth" 
                               value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                               max="{{ now()->subYears(13)->format('Y-m-d') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:w-auto">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Bio Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">About Me</h2>
            </div>
            <div class="px-6 py-6">
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700">
                        Bio
                    </label>
                    <textarea name="bio" 
                              id="bio" 
                              rows="4" 
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Tell others about yourself, your interests, goals, etc.">{{ old('bio', $student->bio) }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">
                        Brief description for your profile. Maximum 1000 characters.
                    </p>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('student.profile.show') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <span class="material-icons mr-2 text-sm">save</span>
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
// Avatar preview functionality
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            preview.innerHTML = `<img class="h-20 w-20 object-cover rounded-full" src="${e.target.result}" alt="Avatar preview">`;
        };
        reader.readAsDataURL(file);
    }
});

// Delete avatar function
function deleteAvatar() {
    if (confirm('Are you sure you want to remove your profile photo?')) {
        fetch('{{ route("student.profile.delete-avatar") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update preview to show default avatar
                const preview = document.getElementById('avatar-preview');
                preview.innerHTML = `
                    <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-500 text-xl font-medium">
                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                        </span>
                    </div>
                `;
                
                // Hide delete button
                event.target.style.display = 'none';
                
                // Show success message
                const successDiv = document.createElement('div');
                successDiv.className = 'mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg';
                successDiv.innerHTML = `
                    <div class="flex">
                        <span class="material-icons text-green-400 mr-2">check_circle</span>
                        Profile photo removed successfully.
                    </div>
                `;
                document.querySelector('.max-w-4xl').insertBefore(successDiv, document.querySelector('form'));
                
                // Auto-hide success message after 5 seconds
                setTimeout(() => successDiv.remove(), 5000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to remove profile photo. Please try again.');
        });
    }
}
</script>
@endsection