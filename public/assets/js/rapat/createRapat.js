$(document).ready(function () {
    //array untuk menampung data peserta rpat yang dipilih
    let pesertaRapat = [];
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

    //untuk menampilkan daftar user untuk dijadikan sebagai peserta rapat, dengan kondisi sudah memilih waktu mulai dan selesai
    $("#table-peserta-rapat").DataTable({
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
            { data: "name" },
            { data: "email" },
            {
                data: null,
                render: function (data, type, row) {
                    return `<input type="checkbox" ${
                        pesertaRapat.includes(row.id) ? "checked" : ""
                    } class="select-checkbox add-peserta" data-id="${row.id}">`;
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
        const id = $(this).data("id");
        if (pesertaRapat.includes(id)) {
            pesertaRapat = pesertaRapat.filter((item) => item !== id);
        } else {
            pesertaRapat.push(id);
        }
        tablePimpinanRapat.ajax.reload();
        console.log(pesertaRapat);
    });
    //untuk table menampilkan pimpinan rapat
    let tablePimpinanRapat = $("#table-pimpinan-rapat").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: `/rapat/agenda-rapat/ajax-selected-peserta`,
            type: "GET",
            data: function (d) {
                d.id = pesertaRapat.join(",");
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
            { data: "name" },
            { data: "email" },
            {
                data: null,
                render: function (data, type, row) {
                    return `<input type="checkbox" ${
                        pesertaRapat.includes(row.id) ? "checked" : ""
                    } class="select-checkbox add-peserta" data-id="${row.id}">`;
                },
            },
        ],
        pageLength: 10,
        lengthChange: true,
        searching: true,
        ordering: true,
    });
});
