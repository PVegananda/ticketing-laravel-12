@extends('layouts.admin_layouts')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold">Data Pengguna (User)</h2>
            <p class="text-gray-500">Kelola role semua pengguna di sistem.</p>
        </div>
        
        {{-- Form Pencarian --}}
        <form action="{{ route('admin.users.index') }}" method="GET" class="w-full sm:w-auto flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="input input-bordered w-full sm:w-64" />
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body p-0 overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Tanggal Daftar</th>
                        <th>Role Saat Ini</th>
                        <th>Aksi Ubah Role</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td class="font-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->no_hp ?? '-' }}</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                @if($user->role == 'superadmin')
                                    <span class="badge badge-primary text-white">Superadmin</span>
                                @elseif($user->role == 'admin')
                                    <span class="badge badge-info text-white">Admin</span>
                                @else
                                    <span class="badge badge-ghost">User</span>
                                @endif
                            </td>
                            <td>
                                {{-- Form Ubah Role --}}
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="select select-bordered select-sm w-full max-w-[120px]" onchange="this.form.submit()">
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 italic">Anda sendiri</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                Tidak ada data user yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>

</div>
@endsection
