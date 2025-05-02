//untuk menampilkan daftar pegawai untuk dijadikan sebagai peserta rapat
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

    if (pesertaManual.includes(username)) {
        pesertaManual = pesertaManual.filter((item) => item !== username);
    } else if (pesertaKepanitiaan.includes(username)) {
        pesertaKepanitiaan = pesertaKepanitiaan.filter(
            (item) => item !== username
        );
    } else {
        pesertaManual.push(username);
    }
    pesertaRapat = [...new Set([...pesertaManual, ...pesertaKepanitiaan])];

    console.log(pesertaRapat);

    tablePesertaRapat.ajax.reload();
    tablePimpinanRapat.ajax.reload();
    tableNotulisRapat.ajax.reload();
});
