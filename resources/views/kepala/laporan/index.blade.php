@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Pusat Laporan Perpustakaan</h1>
        <p class="text-slate-500">Silakan pilih laporan yang ingin dicetak ke format PDF.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-book text-xl"></i>
            </div>
            <h3 class="text-lg font-bold mb-2">Laporan Data Buku</h3>
            <p class="text-sm text-slate-500 mb-6">Cetak semua daftar koleksi buku yang terdaftar di sistem.</p>
            <a href="{{ route('kepala.laporan.buku_pdf') }}" target="_blank" class="block w-full py-3 bg-blue-600 text-white text-center rounded-xl font-semibold">
                Cetak PDF Buku
            </a>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-users text-xl"></i>
            </div>
            <h3 class="text-lg font-bold mb-2">Laporan Data Pengguna</h3>
            <p class="text-sm text-slate-500 mb-6">Cetak daftar akun Petugas, Anggota, dan Kepala Perpustakaan.</p>
            <a href="{{ route('kepala.laporan.akun_pdf') }}" target="_blank" class="block w-full py-3 bg-purple-600 text-white text-center rounded-xl font-semibold">
                Cetak PDF Akun
            </a>
        </div>
    </div>
</div>
@endsection