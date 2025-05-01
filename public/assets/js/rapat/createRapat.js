$(document).ready(function () {
    //array untuk menampung data peserta rpat yang dipilih
    let pesertaRapat = [];
    let pimpinanRapatUsername = "";
    let notulisRapatUsername = "";
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // untuk menentukan waktu selesai akan menggunakan "SELESAI" atau memaasukkan waktu manual
    let waktuSelesai = $("#waktu-selesai");
    let pilihanWaktuSelesai = $("#pilihan-waktu-selesai");
    waktuSelesai.hide();
    pilihanWaktuSelesai.on("change", function () {
        if ($(this).val() == "manual") {
            waktuSelesai.show();
        } else {
            waktuSelesai.hide();
        }
    });

    //untuk menampilkan pilihan tempat rapat, online atau tempat yang lain secara manual
    let tempatRapat = $("#tempat-rapat-group");
    tempatRapat.hide();
    $("#pilihan-tempat").on("change", function () {
        if ($(this).val() == "custom") {
            $("#tempat-rapat").show();
            tempatRapat.show();
        } else {
            $("#tempat-rapat").hide();
            tempatRapat.hide();
        }
    });

    //untuk menampilkan daftar user untuk dijadikan sebagai peserta rapat
    let tablePesertaRapat = $("#table-peserta-rapat").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: "/rapat/agenda-rapat/ajax-peserta-rapat",
            type: "GET",
            dataSrc: "data",
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.settings._iDisplayStart + meta.row + 1;
                },
            },
            { data: "nama" },
            { data: "user.email" },
            {
                data: null,
                render: function (data, type, row) {
                    return `<input type="checkbox" ${
                        pesertaRapat.includes(row.username) ? "checked" : ""
                    } class="select-checkbox add-peserta" name="peserta[]" data-id="${
                        row.username
                    }">`;
                },
            },
        ],
        pageLength: 10,
        lengthChange: true,
        searching: true,
        ordering: true,
    });
    //function untuk menambahkan data peserta rapat ke dalam array
    $("#table-peserta-rapat").on("click", ".add-peserta", function (event) {
        const username = $(this).data("id");
        if (pesertaRapat.includes(username)) {
            pesertaRapat = pesertaRapat.filter((item) => item !== username);
        } else {
            pesertaRapat.push(username);
        }
        tablePimpinanRapat.ajax.reload();
        tableNotulisRapat.ajax.reload();
    });
    //untuk table menampilkan pimpinan rapat
    let tablePimpinanRapat = $("#table-pimpinan-rapat").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: `/rapat/agenda-rapat/ajax-selected-peserta`,
            type: "GET",
            data: function (d) {
                d.username = pesertaRapat.join(",");
            },
            dataSrc: "data",
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.settings._iDisplayStart + meta.row + 1;
                },
            },
            { data: "nama" },
            { data: "user.email" },
            {
                data: null,
                render: function (data, type, row) {
                    if (row.username == notulisRapatUsername) {
                        return "";
                    }
                    return `<input type="radio" name="pimpinan_username" class="select-radio select-pimpinan" data-id="${row.username}">`;
                },
            },
        ],
        pageLength: 10,
        lengthChange: true,
        searching: true,
        ordering: true,
    });
    $("#table-pimpinan-rapat").on(
        "click",
        ".select-pimpinan",
        function (event) {
            pimpinanRapatUsername = $(this).data("id");
            tableNotulisRapat.draw();
        }
    );
    //untuk table menampilkan notulis rapat
    let tableNotulisRapat = $("#table-notulis-rapat").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: `/rapat/agenda-rapat/ajax-selected-peserta`,
            type: "GET",
            data: function (d) {
                d.username = pesertaRapat.join(",");
            },
            dataSrc: "data",
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.settings._iDisplayStart + meta.row + 1;
                },
            },
            { data: "nama" },
            { data: "user.email" },
            {
                data: null,
                render: function (data, type, row) {
                    if (row.username == pimpinanRapatUsername) {
                        return "";
                    }
                    return `<input type="radio" name="notulis_username" ${
                        pimpinanRapatUsername === row.username ? "checked" : ""
                    } class="select-radio select-notulis" data-id="${
                        row.username
                    }">`;
                },
            },
        ],
        pageLength: 10,
        lengthChange: true,
        searching: true,
        ordering: true,
    });
    $("#table-notulis-rapat").on("click", ".select-notulis", function (event) {
        notulisRapatUsername = $(this).data("id");
        tablePimpinanRapat.draw();
    });

    //untuk handle jika rapat adalah rapat kepanitiaan, maka auto check pada peserta rapat sesuai
    //anggota kepanitiaan
    $("#kepanitiaan").on("change", function () {
        let kepanitiaan_id = $(this).val();
        let kepanitiaanPegawai = [];

        $.ajax({
            url: `/rapat/agenda-rapat/ajax-kepanitiaan/${
                kepanitiaan_id ? kepanitiaan_id : "-"
            }`,
            type: "GET",
            dataSrc: "data",
            success: function (response) {
                kepanitiaanPegawai = response.pegawai.map((pegawai) => {
                    return pegawai.username;
                });
                pesertaRapat = [...pesertaRapat, ...kepanitiaanPegawai];
                pesertaRapat = [...new Set(pesertaRapat)];
                console.log(pesertaRapat);

                tablePesertaRapat.ajax.reload();
                tablePimpinanRapat.ajax.reload();
                tableNotulisRapat.ajax.reload();
            },
        });
    });

    //untuk handle submit form pada form tambah agenda rapat
    $("#form-agenda-rapat").on("submit", function (e) {
        e.preventDefault();

        let pilihanTempat = $("#pilihan-tempat").val();
        const waktuMulai = formatDateTimeLocalToYMDHIS($("#waktu-mulai").val());
        const waktuSelesai = formatDateTimeLocalToYMDHIS(
            $("#waktu-selesai").val()
        );
        let formData = new FormData(this);

        let tempat =
            pilihanTempat === "zoom"
                ? "zoom"
                : $('input[name="tempat_rapat"]').val();

        formData.append("nomor_surat", $('input[name="nomor_surat"]').val());
        formData.append("waktu_mulai", waktuMulai);
        formData.append("waktu_selesai", waktuSelesai);
        formData.append("tempat", tempat);
        formData.append(
            "agenda_rapat",
            $('textarea[name="agenda_rapat"]').val()
        );
        pesertaRapat.forEach((username) => {
            formData.append("peserta_rapat[]", username);
        });
        formData.append("pimpinan_username", pimpinanRapatUsername);
        formData.append("notulis_username", notulisRapatUsername);
        formData.append(
            "kepanitiaan_id",
            $('select[name="kepanitiaan_id"]').val()
        );
        $.ajax({
            url: "/rapat/agenda-rapat/store",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $(".invalid-feedback").text("");
                $("input, select, textarea").removeClass("is-invalid");
                console.log(response);
            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
                let errors = xhr.responseJSON.errors;
                $(".invalid-feedback").text("");
                $("input, select, textarea").removeClass("is-invalid");

                Object.keys(errors).forEach(function (key) {
                    let field = key.replace(".", "_");
                    $(`#error-${field}`).text(errors[key][0]);
                    $(`[name="${key}"]`).addClass("is-invalid");
                });
                // Reset dan tampilkan container error
                $("#form-errors").removeClass("d-none");
                $("#form-errors-list").html("");

                // Loop semua pesan error dan tampilkan dalam list
                Object.keys(errors).forEach(function (key) {
                    errors[key].forEach(function (message) {
                        $("#form-errors-list").append(`<li>${message}</li>`);
                    });
                });

                // Optional: scroll ke atas ke pesan error
                $("html, body").animate(
                    {
                        scrollTop: $("#form-errors").offset().top - 100,
                    },
                    500
                );
            },
        });
    });
});
function formatDateTimeLocalToYMDHIS(value) {
    if (!value) return "";
    const [date, time] = value.split("T");
    return `${date} ${time}:00`;
}
