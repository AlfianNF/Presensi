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
                    <th class="px-6 py-3 text-left text-sm font-medium">Jam Absen</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Jam Pulang</th>
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
                    <td class="px-6 py-4">{{ $p->jam_absen }}</td>
                    <td class="px-6 py-4">{{ $p->jam_pulang }}</td>
                    <td class="px-6 py-4 space-x-2">
                        <button class="bg-green-500 text-white px-3 py-1 rounded showBtn"
                            data-id="{{ $p->id }}"
                            data-user="{{ $p->user->username }}"
                            data-hari="{{ $p->hari }}"
                            data-jam_absen="{{ $p->jam_absen }}"
                            data-jam_pulang="{{ $p->jam_pulang }}">
                            Lihat
                        </button>

                        <button class="bg-yellow-500 text-white px-3 py-1 rounded editBtn"
                            data-id="{{ $p->id }}"
                            data-hari="{{ $p->hari }}"
                            data-jam_absen="{{ $p->jam_absen }}"
                            data-jam_pulang="{{ $p->jam_pulang }}">
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
                    <label for="add_jam_absen" class="block font-medium">Jam Absen</label>
                    <input type="time" id="add_jam_absen" name="jam_absen" class="w-full border rounded px-3 py-2"  required>
                </div>
                <div>
                    <label for="add_jam_pulang" class="block font-medium">Jam Pulang</label>
                    <input type="time" id="add_jam_pulang" name="jam_pulang" class="w-full border rounded px-3 py-2"  required>
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
                <p><strong>Jam Absen:</strong> <span id="show_jam_absen"></span></p>
                <p><strong>Jam Pulang:</strong> <span id="show_jam_pulang"></span></p>
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
                    <label for="edit_jam_absen" class="block font-medium">Jam Absen</label>
                    <input type="time" id="edit_jam_absen" name="jam_absen" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label for="edit_jam_pulang" class="block font-medium">Jam Pulang</label>
                    <input type="time" id="edit_jam_pulang" name="jam_pulang" class="w-full border rounded px-3 py-2" required>
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
<script>
document.addEventListener("DOMContentLoaded", function () {
    const baseUrl = document.querySelector('meta[name="app-url"]').getAttribute("content");
    const token = localStorage.getItem("token");

    // Utility: buka/tutup modal
    function toggleModal(modal, show = true) {
        modal.classList.toggle("hidden", !show);
    }

    // Tambah Presensi
    document.getElementById("addPresensiButton")?.addEventListener("click", () => toggleModal(presensiModalAdd, true));
    document.getElementById("closeModalAdd")?.addEventListener("click", () => toggleModal(presensiModalAdd, false));

    // Tampilkan Detail
    document.querySelectorAll(".showBtn").forEach(btn => {
        btn.addEventListener("click", () => {
            document.getElementById("show_user").textContent = btn.dataset.user;
            const formattedHari = formatTanggalIndonesia(btn.dataset.hari);
            document.getElementById("show_hari").textContent = formattedHari;
            document.getElementById("show_jam_absen").textContent = btn.dataset.jam_absen;
            document.getElementById("show_jam_pulang").textContent = btn.dataset.jam_pulang;
            toggleModal(presensiModalShow, true);
        });
    });
    document.getElementById("closeModalShow")?.addEventListener("click", () => toggleModal(presensiModalShow, false));

    // Edit Presensi
    document.querySelectorAll(".editBtn").forEach(btn => {
        btn.addEventListener("click", () => {
            document.getElementById("edit_id").value = btn.dataset.id;
            document.getElementById("edit_hari").value = btn.dataset.hari;
            document.getElementById("edit_jam_absen").value = btn.dataset.jam_absen;
            document.getElementById("edit_jam_pulang").value = btn.dataset.jam_pulang;
            toggleModal(presensiModalEdit, true);
        });
    });
    document.getElementById("closeModalEdit")?.addEventListener("click", () => toggleModal(presensiModalEdit, false));

    // Hapus Presensi
    document.querySelectorAll(".deleteBtn").forEach(btn => {
        btn.addEventListener("click", () => {
            document.getElementById("deletePresensi").dataset.id = btn.dataset.id;
            toggleModal(presensiModalDelete, true);
        });
    });
    document.getElementById("closeModalDelete")?.addEventListener("click", () => toggleModal(presensiModalDelete, false));

    // Fungsi ambil ID user dari /api/me
    async function getUserId() {
        const res = await fetch(`${baseUrl}/api/me`, {
            headers: { Authorization: `Bearer ${token}`, Accept: "application/json" },
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || "Gagal ambil user");
        return data.id;
    }

    // Tambah Data Presensi
    document.getElementById("presensiFormAdd")?.addEventListener("submit", async function (e) {
        e.preventDefault();
        if (!token) return showError("Token tidak ditemukan. Silakan login ulang.");

        try {
            const userId = await getUserId();
            const hari = document.getElementById("add_hari").value;
            const jam_absen = document.getElementById("add_jam_absen").value;
            const jam_pulang = document.getElementById("add_jam_pulang").value;

            const res = await fetch(`${baseUrl}/api/setting-presensi`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                    Accept: "application/json",
                },
                body: JSON.stringify({ hari, jam_absen, jam_pulang, id_user: userId }),
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data.message);
            showSuccess(data.message);
        } catch (error) {
            showError(error.message);
        }
    });

    // Edit Data Presensi
    document.getElementById("presensiFormEdit")?.addEventListener("submit", async function (e) {
        e.preventDefault();
        if (!token) return showError("Token tidak ditemukan. Silakan login ulang.");

        try {
            const userId = await getUserId();
            const id = document.getElementById("edit_id").value;
            const hari = document.getElementById("edit_hari").value;
            const jam_absen = document.getElementById("edit_jam_absen").value;
            const jam_pulang = document.getElementById("edit_jam_pulang").value;


            const res = await fetch(`${baseUrl}/api/setting-presensi/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                    Accept: "application/json",
                },
                body: JSON.stringify({ hari, jam_absen, jam_pulang, id_user: userId }),
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data.message);
            showSuccess(data.message);
        } catch (error) {
            showError(error.message);
        }
    });

    // Hapus Data Presensi
    document.getElementById("deletePresensi")?.addEventListener("click", async function () {
        const id = this.dataset.id;
        if (!token) return showError("Token tidak ditemukan. Silakan login ulang.");

        try {
            const res = await fetch(`${baseUrl}/api/setting-presensi/${id}`, {
                method: "DELETE",
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: "application/json",
                },
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message);
            showSuccess(data.message);
        } catch (error) {
            showError(error.message);
        }
    });

    // SweetAlert helpers
    function showSuccess(msg) {
        Swal.fire({ icon: "success", title: "Berhasil", text: msg, timer: 2000, showConfirmButton: false });
        setTimeout(() => window.location.reload(), 2000);
    }

    function showError(msg) {
        Swal.fire({ icon: "error", title: "Gagal", text: msg || "Terjadi kesalahan." });
    }

    function formatTanggalIndonesia(tanggalString) {
        const tanggal = new Date(tanggalString);
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        return tanggal.toLocaleDateString('id-ID', options);
    }

});    
</script>
@endsection