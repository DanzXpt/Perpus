@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Verifikasi Pengajuan</h1>
            <p class="text-sm text-slate-500">Daftar anggota yang ingin meminjam buku. Silakan tinjau dan berikan persetujuan.</p>
        </div>
        <div class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-2xl text-xs font-bold border border-indigo-100">
            Total: {{ $pengajuan->count() }} Pengajuan
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl flex justify-between items-center animate-fade-in">
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center w-16">No</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Peminjam</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Buku</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pengajuan as $index => $data)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 text-sm text-slate-500 text-center font-medium">{{ $index + 1 }}</td>
                    
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800 text-sm leading-tight">{{ $data->user->name }}</p>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-bold">ID: #{{ $data->user_id }}</p>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-14 bg-slate-100 rounded-lg overflow-hidden shrink-0 shadow-sm border border-gray-100">
                                <img src="{{ asset('storage/'.$data->buku->cover) }}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/150x200?text=No+Cover'">
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm leading-tight">{{ $data->buku->judul }}</p>
                                <p class="text-[10px] text-indigo-500 font-bold uppercase mt-1 italic tracking-wider">Tersedia: {{ $data->buku->stok }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        @if($data->status == 'diajukan')
                            <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-full text-[10px] font-bold uppercase">Diajukan</span>
                        @elseif($data->status == 'dipinjam')
                            <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-[10px] font-bold uppercase">Dipinjam</span>
                        @else
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[10px] font-bold uppercase">Kembali</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            
                            {{-- AKSI JIKA MASIH DIAJUKAN --}}
                            @if($data->status == 'diajukan')
                                <form action="{{ route('petugas.setujui', $data->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="h-9 px-4 bg-emerald-500 text-white rounded-xl text-xs font-bold hover:bg-emerald-600 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-check text-[10px]"></i> Setujui
                                    </button>
                                </form>

                                <form action="{{ route('petugas.tolak', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak pengajuan ini?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="h-9 px-4 bg-white border border-red-200 text-red-500 rounded-xl text-xs font-bold hover:bg-red-50 transition flex items-center gap-2">
                                        <i class="fas fa-times text-[10px]"></i> Tolak
                                    </button>
                                </form>

                            {{-- AKSI JIKA SEDANG DIPINJAM --}}
                            @elseif($data->status == 'dipinjam')
                                <form action="{{ route('petugas.kembali', $data->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="h-9 px-4 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center gap-2">
                                        <i class="fas fa-undo-alt text-[10px]"></i> Selesai / Kembali
                                    </button>
                                </form>

                            {{-- JIKA SUDAH KEMBALI --}}
                            @else
                                <div class="text-emerald-500 flex items-center gap-1 font-bold text-[10px] uppercase">
                                    <i class="fas fa-check-double"></i> Selesai Terverifikasi
                                </div>
                            @endif
                            
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 border border-slate-100">
                            <i class="fas fa-clipboard-check text-2xl"></i>
                        </div>
                        <p class="font-medium">Belum ada pengajuan peminjaman baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection