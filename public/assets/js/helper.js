let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000
});


$(document).ready(function () {
    $('#pangkat').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih opsi",
        allowClear: true,
        dropdownCssClass: 'select2-dropdown-phoenix'
    });
});



const toastSuccess = (message) => {
    Toast.fire({
        icon: 'success',
        title: message
    });
};

const toastError = (message) => {
    let errorText = message; // Inisialisasi default sebagai string error

    // Cek apakah pesan berbentuk objek atau string
    if (typeof message === 'object' && message.errors) {
        errorText = ''; // Reset jika ada detail errors
        for (let key in message.errors) {
            errorText = message.errors[key][0]; // Ambil error pertama
            break;
        }
    } else if (typeof message === 'string') {
        errorText = message;
    }

    Toast.fire({
        icon: 'error',
        title: 'Error <br>' + errorText
    });
};



const startLoading = () => {
    Swal.fire({
        title: "Processing...",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

const stopLoading = () => {
    Swal.close(); // Menutup indikator loading
};


function showSuccessMessage() {
    toastSuccess('Data berhasil ditambahkan!');
}

function showErrorMessage(errorJson) {
    toastError(errorJson);
}

// Menambahkan listener pada tombol untuk memicu notifikasi 
document.addEventListener('DOMContentLoaded', function () {
    if (sessionStorage.getItem('success')) {
        toastSuccess(sessionStorage.getItem('success'));
        sessionStorage.removeItem('success');
    }

    if (sessionStorage.getItem('error')) {
        toastError(JSON.stringify({ errors: { message: sessionStorage.getItem('error') } }));
        sessionStorage.removeItem('error');
    }
});



$(document).ready(function () {
    // Trigger the modal when "Select Atasan" button is clicked
    $('#selectAtasanBtn').on('click', function () {
        $('#atasanModal').modal('show');
    });

    // Delegated event listener for dynamically loaded content
    $('#atasanModal').on('click', '.select-atasan', function () {
        var atasanId = $(this).data('id');
        var atasanName = $(this).data('name');
        var atasanPangkat = $(this).data('pangkat');
        var atasanUnitKerja = $(this).data('unit-kerja');
        var atasanJabatan = $(this).data('jabatan');

        // Set the selected atasan's data into the form inputs
        $('#atasan_id_input').val(atasanId); // Hidden input for atasan ID
        $('#atasan_name').val(atasanName); // Disabled input for atasan name
        $('#atasan_pangkat').val(atasanPangkat); // Disabled input for atasan pangkat
        $('#atasan_unit_kerja').val(atasanUnitKerja); // Disabled input for unit kerja
        $('#atasan_jabatan').val(atasanJabatan); // Disabled input for jabatan

        // Close the modal after selection
        $('#atasanModal').modal('hide');
    });
});










