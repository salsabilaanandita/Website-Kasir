@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="px-4 py-4">
    {{-- Header Section --}}
    <div class="flex flex-wrap items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Users Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage system users and their roles</p>
        </div>
        <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-5 rounded-full shadow-sm transition-all inline-flex items-center gap-2">
            <i class="fas fa-plus text-sm"></i> Add New User
        </a>
    </div>

    {{-- Users Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="px-5 py-3 border-b border-gray-100 flex flex-wrap items-center justify-between">
            <h6 class="font-bold text-blue-600">
                <i class="fas fa-users mr-2"></i>Users List
            </h6>
            <span class="bg-gray-100 text-gray-500 text-xs font-medium px-3 py-1.5 rounded-full">
                <i class="fas fa-database mr-1 text-xs"></i> Total: {{ $users->count() }} users
            </span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-left">
                        <th class="py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide text-center w-16">No</th>
                        <th class="py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide">Name</th>
                        <th class="py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide">Email</th>
                        <th class="py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide text-center w-24">Role</th>
                        <th class="py-3 px-4 text-gray-500 text-xs font-semibold uppercase tracking-wide text-center w-24">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="py-3 px-4 text-center">
                            <span class="text-gray-600 text-sm">{{ $loop->iteration }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="text-gray-700 font-medium text-sm">{{ $user->name }}</span>
                                    <p class="text-gray-400 text-xs">ID: {{ $user->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1 text-gray-500 text-sm">
                                <i class="fas fa-envelope text-gray-400 text-xs"></i>
                                {{ $user->email }}
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                                    <i class="fas fa-crown text-xs"></i> Admin
                                </span>
                            @elseif($user->role === 'staff')
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                                    <i class="fas fa-user-check text-xs"></i> Staff
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-1.5 rounded-full">
                                    <i class="fas fa-user text-xs"></i> {{ ucfirst($user->role) }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-all" title="Edit User">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete User">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-gray-400 text-3xl"></i>
                                </div>
                                <p class="text-gray-500 mb-2">No users found</p>
                                <p class="text-gray-400 text-sm">Get started by adding your first user</p>
                                <a href="{{ route('users.create') }}" class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 py-2 rounded-full mt-3 transition-all">
                                    <i class="fas fa-plus text-sm"></i> Add New User
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection