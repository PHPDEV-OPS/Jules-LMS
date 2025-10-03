@extends('layouts.admin')

@section('title', 'Certificates')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Certificates</h1>
            <p class="mt-1 text-sm text-gray-500">Manage student certificates and awards</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.certificates.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons text-sm mr-2">add</span>
                Issue Certificate
            </a>
        </div>
    </div>
    
    <div class="bg-white shadow rounded-lg p-8 text-center">
        <span class="material-icons text-6xl text-gray-300">verified</span>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Certificate Management</h3>
        <p class="mt-2 text-sm text-gray-500">Issue and manage certificates for completed courses.</p>
    </div>
</div>
@endsection