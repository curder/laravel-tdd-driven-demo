@extends('layouts.app')

@section('content')
    <div class="w-2/3 p-6 bg-gray-200 mx-auto">
        <form action="/authors" method="POST" class="flex flex-col items-center space-y-4">
            @csrf

            <h1>Add New Author</h1>

            <div>
                <input type="text" name="name" placeholder="Full Name" class="rounded px-4 py-2 w-64">
                @error('name')
                <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <input type="text" name="dob" placeholder="Date of Birthday" class="rounded px-4 py-2 w-64">
                @error('dob')
                <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <button class="bg-blue-400 text-white rounded py-2 px-4">Add New Author</button>
            </div>
        </form>
    </div>
@endsection
