<form action="{{ route('anggota.pinjam', $b) }}" method="POST">
    @csrf
    <button type="submit" 
        class="w-full py-2 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
        Ajukan Pinjam
    </button>
</form>