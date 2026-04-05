@extends('layouts.app')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    
    <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tighter">Dashboard</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Selamat datang kembali, {{ Auth::user()->name }}!</p>
        </div>
        <div class="flex items-center gap-6">
            <div class="hidden md:flex flex-col items-end border-r border-slate-100 pr-6">
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-widest">Waktu Sistem</span>
                <span class="text-sm font-black text-slate-700">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</span>
            </div>
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-black shadow-lg shadow-blue-100 text-lg">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
        
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4 group hover:border-blue-100 transition-all duration-300">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800 leading-tight">{{ $totalBuku }}</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">Total<br>Buku</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4 group hover:border-blue-100 transition-all duration-300">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800 leading-tight">{{ $totalAnggota }}</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">Anggota<br>Aktif</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4 group hover:border-emerald-100 transition-all duration-300">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800 leading-tight">{{ $totalDipinjam }}</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">Sedang<br>Dipinjam</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4 group hover:border-red-100 transition-all duration-300">
            <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center text-xl shrink-0 group-hover:bg-red-600 group-hover:text-white transition-all shadow-sm">
                <i class="fas fa-history"></i>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800 leading-tight">{{ $totalTerlambat }}</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">Terlambat</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4 group hover:border-amber-100 transition-all duration-300">
            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl shrink-0 group-hover:bg-amber-600 group-hover:text-white transition-all shadow-sm">
                <i class="fas fa-sack-dollar"></i>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800 leading-tight">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">Total<br>Denda</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="col-span-1 lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden group hover:border-blue-100 transition-all">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <h2 class="font-black text-slate-800 text-lg tracking-tight">Peminjaman Terbaru</h2>
                <a href="{{ route('petugas.transaksi') }}" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-md shadow-blue-600/20">
                    Lihat Semua <i class="fas fa-chevron-right ml-1"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kode</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Anggota</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Buku</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl Pinjam</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($peminjamanTerbaru as $item)
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-8 py-5 text-xs font-bold text-slate-400">#PJM-{{ $item->id }}</td>
                            <td class="px-6 py-5 text-xs font-black text-slate-800 uppercase">{{ $item->user->name }}</td>
                            <td class="px-6 py-5 text-xs font-bold text-slate-700 truncate max-w-xs">{{ $item->buku->judul }}</td>
                            <td class="px-6 py-5 text-xs text-slate-500 font-medium">{{ date('d/m/Y', strtotime($item->tanggal_pinjam)) }}</td>
                            <td class="px-6 py-5">
                                @if($item->status == 'dipinjam')
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-wider">Dipinjam</span>
                                @elseif($item->status == 'terlambat')
                                    <span class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-black uppercase tracking-wider">Terlambat</span>
                                @else
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-wider">Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-slate-400 text-sm font-medium">Belum ada data peminjaman terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col group hover:border-blue-100 transition-all">
            <div class="flex items-center gap-3 mb-8">
                <i class="fas fa-bolt text-blue-500 text-2xl"></i>
                <h2 class="font-black text-slate-800 text-lg tracking-tight">Aksi Cepat</h2>
            </div>

            <div class="flex flex-col gap-4 mt-auto">
                <a href="{{ route('petugas.transaksi') }}" class="w-full flex items-center justify-center gap-3 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-md shadow-blue-600/10 active:scale-95 group/btn">
                    <i class="fas fa-plus group-hover/btn:rotate-90 transition-all"></i> Tambah Peminjaman
                </a>
                
                <a href="{{ route('petugas.transaksi') }}" class="w-full flex items-center justify-center gap-3 py-4 bg-emerald-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-emerald-600 transition-all active:scale-95 group/btn">
                    <i class="fas fa-sign-in-alt group-hover/btn:-translate-x-1 transition-all"></i> Proses Pengembalian
                </a>
                
                <a href="{{ route('petugas.buku.create') }}" class="w-full flex items-center justify-center gap-3 py-4 border-2 border-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 hover:border-slate-200 transition-all active:scale-95 group/btn">
                    <i class="fas fa-book group-hover/btn:scale-110 transition-all"></i> Tambah Buku
                </a>
                
                {{-- Tambah Anggota (Resource akun biasanya di Kepala) --}}
                {{-- <a href="{{ route('kepala.akun.create') }}" class="w-full flex items-center justify-center gap-3 py-4 border-2 border-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 hover:border-slate-200 transition-all active:scale-95 group/btn">
                    <i class="fas fa-user-plus group-hover/btn:scale-110 transition-all"></i> Tambah Anggota
                </a> --}}
            </div>
        </div>
    </div>
</div>
@endsection