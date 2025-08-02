$(document).ready(function () {
    var groupColumn = 1; // Kolom kedua (indeks 1) untuk pengelompokan
    var table = $('#tableSkp').DataTable({
        columnDefs: [
            { visible: false, targets: groupColumn } // Sembunyikan kolom Tahun
        ],
        order: [[groupColumn, 'asc']], // Urutkan berdasarkan Tahun
        drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;

            api.column(groupColumn, { page: 'current' })
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="9" class="fw-bold bg-light text-center">' + group +
                            '</td></tr>'
                        );
                        last = group;
                    }
                });
        },
        language: {
            emptyTable: "Tidak ada data yang tersedia",
            info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 hingga 0 dari 0 data",
            lengthMenu: "Tampilkan _MENU_ data",
            search: "Cari:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Event klik pada grup untuk mengurutkan data
    $('#tableSkp tbody').on('click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
            table.order([groupColumn, 'desc']).draw();
        } else {
            table.order([groupColumn, 'asc']).draw();
        }
    });
});


$(document).ready(function () {
    var groupColumn = 1; // Kolom kedua untuk pengelompokan (Rencana Hasil Kerja)
    var table = $('#tableRencana').DataTable({
        columnDefs: [
            { orderable: false, targets: [0, 1, 2, 3, 4, 5, 6] }, // Kolom No dan Action tidak dapat diurutkan
            { visible: false, targets: groupColumn }, // Kolom untuk grup disembunyikan
        ],
        order: [[groupColumn, 'asc']], // Urutkan berdasarkan grup
        paging: true, // Aktifkan pagination
        info: false, // Nonaktifkan informasi jumlah data
        searching: true, // Nonaktifkan fitur pencarian
        language: {
            emptyTable: "Tidak ada data yang tersedia",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya",
            },
        },
        drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;

            // Tambahkan baris grup sebelum baris detail
            api.column(groupColumn, { page: 'current' })
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="7" class="fw-bold bg-light">' + group +
                            '</td></tr>'
                        );
                        last = group;
                    }
                });

            // Tambahkan styling khusus untuk baris grup
            $('.group').css({
                'background-color': '#f8f9fa',
                'color': '#495057',
                'font-weight': 'bold',
            });
        },
    });

    // Event klik pada grup untuk mengurutkan data
    $('#tableRencana tbody').on('click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
            table.order([groupColumn, 'desc']).draw();
        } else {
            table.order([groupColumn, 'asc']).draw();
        }
    });
});


// Fungsi untuk menghapus data dengan konfirmasi SweetAlert
const deleteData = (e) => {
    let uuid = e.getAttribute('data-uuid'); // Mendapatkan data-uuid

    if (!uuid) {
        Swal.fire({
            title: "Error!",
            text: "Invalid UUID!",
            icon: "error"
        });
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete this item?",
        icon: "question",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        allowOutsideClick: false,
        showCancelButton: true,
        showCloseButton: true
    }).then((result) => {
        if (result.isConfirmed) { // Jika pengguna mengonfirmasi penghapusan
            startLoading(); // Menampilkan loading indikator (pastikan fungsi ini sudah diimplementasi)

            $.ajax({
                type: "DELETE",
                url: `/skp/destroy/${uuid}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    stopLoading(); // Menghentikan loading indikator
                    toastSuccess(response.message); // Menampilkan notifikasi sukses

                    // Hapus baris dari DataTable
                    var table = $('#tableSkp').DataTable();
                    table.rows().every(function () {
                        var row = this.node();
                        if ($(row).data('uuid') === uuid) {
                            table.row(row).remove();  // Hapus baris dari tabel
                        }
                    });
                    table.draw(); // Redraw tabel untuk memperbarui tampilan
                },
                error: function (xhr, status, error) {
                    stopLoading(); // Menghentikan loading indikator jika ada error
                    console.error("Error:", error); // Menampilkan pesan error di konsol
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to delete the data. Please try again.",
                        icon: "error",
                        confirmButtonColor: "#3085d6"
                    });
                }
            });
        }
    });
}

// Fungsi untuk menghapus baris dari DataTable
const removeRowFromTable = (uuid) => {
    var table = $('#tableSkp').DataTable();
    table.rows().every(function () {
        var row = this.node();
        if ($(row).data('uuid') === uuid) {
            table.row(row).remove();  // Hapus baris dari tabel
        }
    });
    table.draw();  // Redraw DataTable untuk memperbarui tampilan
}

const deleteDataIndikator = (e) => {
    let uuid = e.getAttribute('data-uuid'); // Mendapatkan data-uuid

    if (!uuid) {
        Swal.fire({
            title: "Error!",
            text: "Invalid UUID!",
            icon: "error"
        });
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete this item?",
        icon: "question",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        allowOutsideClick: false,
        showCancelButton: true,
        showCloseButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            startLoading(); // Menampilkan indikator loading (pastikan ini sudah diimplementasikan)

            $.ajax({
                type: "DELETE",
                url: `/indikator-kinerja/destroy/${uuid}`, // Perbaiki URL
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    stopLoading(); // Menghentikan loading indikator
                    toastSuccess(response.message); // Menampilkan notifikasi sukses

                    // Hapus baris dari DataTable
                    var table = $('#tableRencana').DataTable(); // Pastikan DataTable sudah diinisialisasi
                    table.rows(`[data-uuid="${uuid}"]`).remove().draw(); // Hapus baris dan redraw tabel
                },
                error: function (xhr, status, error) {
                    stopLoading(); // Menghentikan loading indikator jika ada error
                    console.error("Error:", error); // Menampilkan pesan error di konsol
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to delete the data. Please try again.",
                        icon: "error",
                        confirmButtonColor: "#3085d6"
                    });
                }
            });
        }
    });
};



// Fungsi untuk menghapus baris dari DataTable
const removeRowFromTableIndikator = (uuid) => {
    var table = $('#tableRencana').DataTable();
    table.rows().every(function () {
        var row = this.node();
        if ($(row).data('uuid') === uuid) {
            table.row(row).remove();  // Hapus baris dari tabel
        }
    });
    table.draw();  // Redraw DataTable untuk memperbarui tampilan
}



function confirmSubmit() {
    Swal.fire({
        title: 'Ajukan SKP?',
        text: "Anda tidak dapat mengubah data setelah diajukan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, ajukan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Arahkan form untuk pengajuan SKP
            const form = document.getElementById('form-skp');
            form.action = form.getAttribute('data-submit-url'); // URL untuk pengajuan
            form.submit();
        }
    });
}

function confirmToggle(skpId, status) {
    const action = status ? 'mengaktifkan' : 'menonaktifkan';
    Swal.fire({
        title: `Apakah Anda yakin ingin ${action} SKP ini?`,
        text: "Perubahan ini akan mempengaruhi status SKP.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `Ya, ${action}!`,
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Arahkan form untuk aktivasi SKP
            const form = document.getElementById('form-skp');
            form.action = form.getAttribute('data-toggle-url'); // URL untuk aktivasi
            form.submit();
        }
    });
}


const openEditIndikatorModal = (uuid, rencanaPegawaiId, aspek, indikatorKinerja, tipeTarget, targetMinimum,
    targetMaximum, satuan, report) => {
    console.log(`UUID indikator: ${uuid}`); // Menampilkan UUID indikator di console untuk verifikasi
    // Isi data ke dalam form edit
    $('#edit-uuid').val(uuid);
    $('#editRencanaPegawai').val(rencanaPegawaiId);
    $('#editAspek').val(aspek);
    $('#editIndikatorKinerja').val(indikatorKinerja);
    $('#editTipeTarget').val(tipeTarget);
    $('#editTargetMinimum').val(targetMinimum);
    $('#editTargetMaximum').val(targetMaximum);
    $('#editSatuan').val(satuan);
    $('#editReport').val(report);

    // Tampilkan modal
    $('#modalEditIndikator').modal('show');

    $(document).ready(function () {
        $('#editRencanaPegawai').select2({
            theme: "bootstrap-5",
            placeholder: "Pilih Rencana",
            allowClear: true,
        });
    });
};


// Fungsi untuk menangani form submit edit indikator
$('#formEditIndikator').submit(function (e) {
    e.preventDefault(); // Mencegah perilaku default submit form

    // Ambil data dari form
    const uuid = $('#edit-uuid').val();
    const rencanaPegawaiId = $('#editRencanaPegawai').val();
    const aspek = $('#editAspek').val();
    const indikatorKinerja = $('#editIndikatorKinerja').val();
    const tipeTarget = $('#editTipeTarget').val();
    const targetMinimum = $('#editTargetMinimum').val();
    const targetMaximum = $('#editTargetMaximum').val();
    const satuan = $('#editSatuan').val();
    const report = $('#editReport').val();

    console.log(`Mengupdate indikator dengan UUID: ${uuid}`); // Debugging

    // Validasi sederhana untuk memastikan data tidak kosong
    if (!uuid || !rencanaPegawaiId || !aspek || !indikatorKinerja || !tipeTarget || !targetMinimum || !satuan) {
        toastError('Harap lengkapi semua data wajib sebelum mengupdate.');
        return;
    }

    // Proses AJAX untuk mengupdate data
    $.ajax({
        type: "PUT", // Gunakan metode HTTP PUT
        url: `/indikator-kinerja/${uuid}/update`, // Pastikan URL endpoint sesuai
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambahkan CSRF token
        },
        data: {
            rencana_kerja_pegawai_id: rencanaPegawaiId,
            aspek: aspek,
            indikator_kinerja: indikatorKinerja,
            tipe_target: tipeTarget,
            target_minimum: targetMinimum,
            target_maksimum: targetMaximum,
            satuan: satuan,
            report: report
        },
        success: function (response) {
            console.log('Success:', response); // Debugging respons sukses
            toastSuccess(response.message ||
                'Indikator berhasil diperbarui.'); // Tampilkan notifikasi sukses
            $('#modalEditIndikator').modal('hide'); // Tutup modal
            location.reload(); // Reload halaman untuk memperbarui tampilan
        },
        error: function (xhr) {
            console.error('Error:', xhr); // Debugging error dari server
            const errorMessage = xhr.responseJSON?.message ||
                'Terjadi kesalahan saat mengupdate indikator.';
            toastError(errorMessage); // Tampilkan pesan error
        }
    });
});


