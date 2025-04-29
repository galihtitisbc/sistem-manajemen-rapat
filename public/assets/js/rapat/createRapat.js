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
    let tablePesertaRapat = $("#peserta-rapat");
    $("#daftar-peserta-rapat").DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: "/rapat/agenda-rapat/ajax-peserta-rapat",
            type: "GET",
            dataSrc: "data",
        },
        columns: [{ data: "id" }, { data: "name" }, { data: "email" }],
        pageLength: 10,
        lengthChange: true,
        searching: true,
        ordering: true,
    });
    //function untuk menambahkan data peserta rapat ke dalam array
    $(".add-peserta").click(function (event) {
        const id = $(event.target).data("id");
        pesertaRapat.push(id);
    });
});
