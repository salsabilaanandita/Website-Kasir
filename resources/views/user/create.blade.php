@extends('layouts.app')
@section('title', 'Create User')

@section('content')
<div class="px-4 py-4">
    {{-- Header Section --}}
    <div class="flex flex-wrap items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create New User</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new user to the system</p>
        </div>
        <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-5 rounded-full shadow-sm transition-all inline-flex items-center gap-2">
            <i class="fas fa-arrow-left text-sm"></i> Back to Users
        </a>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-4">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle mt-0.5 mr-3"></i>
                <div>
                    <strong class="font-semibold">Error!</strong> Please check the following:
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100">
            <h6 class="font-bold text-blue-600">
                <i class="fas fa-user-plus mr-2"></i>User Information
            </h6>
        </div>
        <div class="p-5">
            <form method="POST" action="{{ route('users.store') }}" id="userForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name Field --}}
                    <div>
                        <label for="name" class="font-semibold text-gray-700 block mb-2">Name <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <input type="text" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" 
                                id="name" name="name" value="{{ old('name') }}" 
                                required minlength="3" maxlength="255"
                                placeholder="Enter user's full name">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email Field --}}
                    <div>
                        <label for="email" class="font-semibold text-gray-700 block mb-2">Email <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-envelope text-sm"></i>
                            </div>
                            <input type="email" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" 
                                id="email" name="email" value="{{ old('email') }}" 
                                required placeholder="user@example.com">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    {{-- Password Field --}}
                    <div>
                        <label for="password" class="font-semibold text-gray-700 block mb-2">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-lock text-sm"></i>
                            </div>
                            <input type="password" class="w-full pl-9 pr-10 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" 
                                id="password" name="password" required minlength="3"
                                placeholder="Enter password">
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        <p class="text-gray-400 text-xs mt-1">Minimum 3 characters</p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role Field --}}
                    <div>
                        <label for="role" class="font-semibold text-gray-700 block mb-2">Role <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-tag text-sm"></i>
                            </div>
                            <select class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror" 
                                id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                        </div>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3">
                    <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-5 rounded-full transition-all inline-flex items-center gap-2">
                        <i class="fas fa-undo-alt"></i> Reset
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-5 rounded-full transition-all inline-flex items-center gap-2">
                        <i class="fas fa-plus-circle"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="mt-4 bg-blue-50 rounded-xl p-4 border border-blue-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center">
                <i class="fas fa-info-circle text-white"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-800">Role Information</p>
                <p class="text-xs text-blue-600">Admin: Full access | Staff: Limited access (transactions only)</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle Password Visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endpush
@endsection