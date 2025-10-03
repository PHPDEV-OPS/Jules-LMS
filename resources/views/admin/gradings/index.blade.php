@extends('layouts.admin')

@section('title', 'Grading')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Grading</h1>
            <p class="mt-1 text-sm text-gray-500">Grade student submissions and assessments</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.gradings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons text-sm mr-2">add</span>
                Add Grade
            </a>
        </div>
    </div>
    
    <div class="bg-white shadow rounded-lg p-8 text-center">
        <span class="material-icons text-6xl text-gray-300">grade</span>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Grading System</h3>
        <p class="mt-2 text-sm text-gray-500">Grade and provide feedback on student work.</p>
    </div>
</div>
@endsection