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
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if ($settings->isEmpty())
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @else
                @foreach ($settings as $index => $p)
                <tr>
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">{{ $p->user->username }}</td>
                    <td class="px-6 py-4">{{ $p->hari }}</td>
                    <td class="px-6 py-4">{{ $p->waktu }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>

        </table>
    </div>
</main>

{{-- Modal --}}
<div id="presensiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Tambah Presensi</h2>
        <form id="presensiForm" class="space-y-4">
            <input type="hidden" id="id_user" name="id_user"> {{-- Input hidden untuk id_user --}}
            <div>
                <label for="hari" class="block font-medium">Tanggal</label>
                <input type="date" id="hari" name="hari" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label for="waktu" class="block font-medium">Waktu (Jam)</label>
                <input type="time" id="waktu" name="waktu" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                <button type="button" id="closeModal" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/setting.js') }}"></script>
</div>
@endsection
