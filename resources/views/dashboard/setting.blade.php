@extends('component.dashboard')
@section('container')
<main class="flex-1 p-8">
    <h1 class="text-2xl font-bold mb-6">Presensi Harian</h1>

    <div class="mb-4">
        <button id="addPresensiButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Tambah Presensi
        </button>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium">No</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Dibuat oleh</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Tanggal</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Waktu</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if ($settings->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @else
                @foreach ($settings as $index => $p)
                <tr>
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">{{ $p->user->username }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($p->hari)->translatedFormat('d F Y') }}</td>
                    <td class="px-6 py-4">{{ $p->waktu }}</td>
                    <td class="px-6 py-4 space-x-2">
                        <button class="bg-green-500 text-white px-3 py-1 rounded showBtn"
                            data-id="{{ $p->id }}"
                            data-user="{{ $p->user->username }}"
                            data-hari="{{ $p->hari }}"
                            data-waktu="{{ $p->waktu }}">
                            Lihat
                        </button>

                        <button class="bg-yellow-500 text-white px-3 py-1 rounded editBtn"
                            data-id="{{ $p->id }}"
                            data-hari="{{ $p->hari }}"
                            data-waktu="{{ $p->waktu }}">
                            Edit
                        </button>

                        <button class="bg-red-500 text-white px-3 py-1 rounded deleteBtn"
                            data-id="{{ $p->id }}">
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{-- Modal Add --}}
    <div id="presensiModalAdd" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Tambah Presensi</h2>
            <form id="presensiFormAdd" class="space-y-4">
                <input type="hidden" id="add_id_user" name="id_user">
                <div>
                    <label for="add_hari" class="block font-medium">Tanggal</label>
                    <input type="date" id="add_hari" name="hari" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label for="add_waktu" class="block font-medium">Waktu (Jam)</label>
                    <input type="time" id="add_waktu" name="waktu" class="w-full border rounded px-3 py-2" step="1" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                    <button type="button" id="closeModalAdd" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Show --}}
    <div id="presensiModalShow" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Detail Presensi</h2>
            <div id="showData" class="space-y-2">
                <p><strong>Dibuat Oleh:</strong> <span id="show_user"></span></p>
                <p><strong>Tanggal:</strong> <span id="show_hari"></span></p>
                <p><strong>Waktu:</strong> <span id="show_waktu"></span></p>
            </div>
            <div class="flex justify-end mt-4">
                <button type="button" id="closeModalShow" class="bg-gray-300 px-4 py-2 rounded">Tutup</button>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="presensiModalEdit" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Edit Presensi</h2>
            <form id="presensiFormEdit" class="space-y-4">
                <input type="hidden" id="edit_id" name="id">
                <div>
                    <label for="edit_hari" class="block font-medium">Tanggal</label>
                    <input type="date" id="edit_hari" name="hari" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label for="edit_waktu" class="block font-medium">Waktu (Jam)</label>
                    <input type="time" id="edit_waktu" name="waktu" class="w-full border rounded px-3 py-2" step="1" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                    <button type="button" id="closeModalEdit" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div id="presensiModalDelete" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Hapus Presensi</h2>
            <p>Apakah Anda yakin ingin menghapus data presensi ini?</p>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" id="deletePresensi" class="bg-red-600 text-white px-4 py-2 rounded">Hapus</button>
                <button type="button" id="closeModalDelete" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
            </div>
        </div>
    </div>
</main>
<script src="{{ asset('js/setting.js') }}"></script>
</script>
@endsection
