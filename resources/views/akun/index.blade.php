@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        {{-- Header & Welcome --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Pengguna</h1>
                <p class="text-sm text-slate-500">Halo, {{ auth()->user()->name ?? 'Pengguna' }}! Kelola data akun di sini.
                </p>
            </div>
            <a href="{{ route('admin.akun.create') }}"
                class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition-all flex items-center shadow-lg shadow-indigo-200 w-fit">
                <i class="fas fa-plus mr-2 text-xs"></i> Tambah Akun Baru
            </a>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div
                class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl flex justify-between items-center shadow-sm">
                <span class="text-sm font-medium">{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th
                                class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center w-16">
                                No</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Informasi
                                Pengguna</th>
                            <th
                                class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">
                                Level</th>
                            <th
                                class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">
                                Identitas</th>
                            <th
                                class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-400 text-center font-medium">{{ $index + 1 }}</td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-sm">{{ $user->name }}</span>
                                        <span
                                            class="text-xs text-slate-400 font-medium lowercase italic">{{ $user->email }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $badgeColor = match ($user->level) {
                                            'anggota' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'petugas' => 'bg-purple-50 text-purple-600 border-purple-100',
                                            'kepala' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            default => 'bg-slate-50 text-slate-600 border-slate-100'
                                        };
                                    @endphp
                                    <span
                                        class="px-3 py-1 border {{ $badgeColor }} rounded-full text-[10px] font-bold uppercase tracking-tight">
                                        {{ $user->level }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="text-xs font-semibold text-slate-600">
                                        @if($user->level == 'anggota' && $user->anggota)
                                            <span class="text-slate-400 font-normal mr-1 italic">NIS:</span>
                                            {{ $user->anggota->nis }}
                                            <div class="text-[10px] text-indigo-400 font-bold uppercase mt-0.5">
                                                {{ $user->anggota->kelas }}</div>
                                        @elseif($user->level == 'petugas' && $user->petugas)
                                            <span class="text-slate-400 font-normal mr-1 italic">NIP:</span>
                                            {{ $user->petugas->nip_petugas ?? '-' }}
                                        @elseif($user->level == 'kepala' && $user->kepala)
                                            <span class="text-slate-400 font-normal mr-1 italic">NIP:</span>
                                            {{ $user->kepala->nip_kepala ?? '-' }}
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.akun.detail', $user->id) }}"
                                            class="p-2.5 bg-sky-50 text-sky-600 rounded-xl hover:bg-sky-100 transition-colors shadow-sm"
                                            title="Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>

                                        <a href="{{ route('admin.akun.edit', $user->id) }}"
                                            class="p-2.5 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-100 transition-colors shadow-sm"
                                            title="Edit">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>

                                        <form action="{{ route('admin.akun.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus akun ini?')"
                                                class="p-2.5 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-100 transition-colors shadow-sm"
                                                title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-slate-400 italic text-sm">Data pengguna tidak ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection