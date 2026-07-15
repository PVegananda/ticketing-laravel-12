@extends('layouts.admin_layouts')

@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">
                Dashboard
            </h2>
            <p class="text-gray-500 mt-1">
                Ringkasan sistem ticketing.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card Events -->
        <div class="card bg-base-100 shadow hover:shadow-lg transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <h3 class="text-gray-500 font-medium">Total Event</h3>
                    <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-6 h-6"><path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5c0-1.1-.9-2-2-2m0 16H5V8h14zm0-13H5V5h14z"/></svg>
                    </div>
                </div>
                <p class="text-4xl font-bold text-gray-800 mt-2">{{ $totalEvents }}</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.events.index') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline flex items-center">
                        Kelola Event 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Kategori -->
        <div class="card bg-base-100 shadow hover:shadow-lg transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <h3 class="text-gray-500 font-medium">Total Kategori</h3>
                    <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-6 h-6"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h6v6H4zm10 0h6v6h-6zM4 14h6v6H4zm10 3a3 3 0 1 0 6 0a3 3 0 1 0-6 0"/></svg>
                    </div>
                </div>
                <p class="text-4xl font-bold text-gray-800 mt-2">{{ $totalCategories }}</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('categories.index') }}" class="text-sm text-purple-600 hover:text-purple-800 hover:underline flex items-center">
                        Kelola Kategori
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Users -->
        <div class="card bg-base-100 shadow hover:shadow-lg transition-shadow">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <h3 class="text-gray-500 font-medium">Total Pengguna</h3>
                    <div class="p-3 bg-green-100 rounded-full text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-6 h-6"><path fill="currentColor" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                </div>
                <p class="text-4xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <span class="text-sm text-green-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Pengguna Terdaftar
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
