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

            document.getElementById("show_waktu").textContent = btn.dataset.waktu;
            toggleModal(presensiModalShow, true);
        });
    });
    document.getElementById("closeModalShow")?.addEventListener("click", () => toggleModal(presensiModalShow, false));


    // Edit Presensi
    document.querySelectorAll(".editBtn").forEach(btn => {
        btn.addEventListener("click", () => {
            document.getElementById("edit_id").value = btn.dataset.id;
            document.getElementById("edit_hari").value = btn.dataset.hari;
            document.getElementById("edit_waktu").value = btn.dataset.waktu;
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
            const waktu = document.getElementById("add_waktu").value;

            const res = await fetch(`${baseUrl}/api/setting-presensi`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                    Accept: "application/json",
                },
                body: JSON.stringify({ hari, waktu, id_user: userId }),
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
            const waktu = document.getElementById("edit_waktu").value;

            const res = await fetch(`${baseUrl}/api/setting-presensi/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                    Accept: "application/json",
                },
                body: JSON.stringify({ hari, waktu, id_user: userId }),
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
