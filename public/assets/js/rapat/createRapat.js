$(document).ready(function () {
    // untuk menentukan waktu selesai akan menggunakan "SELESAI" atau memaasukkan waktu manual
    let waktuSelesai = $("#waktu-selesai").hide();
    $("#pilihan-waktu-selesai").on("change", function () {
        if ($(this).val() == "manual") {
            $("#waktu-selesai").show();
        } else {
            $("#waktu-selesai").hide();
        }
    });

    //untuk menampilkan pilihan tempat rapat, online atau tempat yang lain secara manual
    $("#tempat-rapat").hide();
    $("#tempat-rapat-group").hide();
    $("#pilihan-tempat").on("change", function () {
        if ($(this).val() == "custom") {
            $("#tempat-rapat").show();
            $("#tempat-rapat-group").show();
        } else {
            $("#tempat-rapat").hide();
            $("#tempat-rapat-group").hide();
        }
    });

    //untuk menampilkan daftar user untuk dijadikan sebagai peserta rapat, dengan kondisi sudah memilih waktu mulai dan selesai
    // $("#peserta-rapat").hide();
});
