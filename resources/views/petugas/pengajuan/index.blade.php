@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6 text-slate-800">Persetujuan Pinjam</h1>

        <div class="grid gap-4">
            @forelse($pengajuan as $p)
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $p->user->name }}</h3>
                        <p class="text-sm text-slate-500">Ingin meminjam: <span
                                class="font-semibold text-indigo-600">{{ $p->buku->judul }}</span></p>
                        <p class="text-xs text-slate-400 mt-1 italic">Diajukan pada: {{ $p->created_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <form action="{{ url('/petugas/pengajuan/' . $p->id . '/setujui') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 px-3 py-1 text-white rounded">
                                Setujui
                            </button>
                        </form>

                        <form action="{{ url('/petugas/pengajuan/' . $p->id . '/tolak') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 px-3 py-1 text-white rounded">
                                Tolak
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center p-12 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                    <p class="text-slate-500">Tidak ada pengajuan pinjaman baru.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection