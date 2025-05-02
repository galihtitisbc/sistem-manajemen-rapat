$(document).ready(function () {
    //declare variable
    let selectWaktuSelesai = $("#pilihan-waktu-selesai");
    let waktuSelesai = $("#waktu-selesai");
    let selectPilihanTempat = $("#pilihan-tempat");
    let tempatRapat = $('input[name="tempat_rapat"]');
    let tempatRapatGroup = $("#tempat-rapat-group");
    //untuk menampilkan pilihan tempat rapat, online atau tempat yang lain secara manual
    tempatRapatGroup.hide();
    tempatRapat.hide();
    waktuSelesai.hide();

    //request ajax untuk menampilkan data form edit
    $.ajax({
        url: `/rapat/agenda-rapat/ajax-edit/${slug}`,
        type: "GET",
        dataSrc: "data",
        success: function (response) {
            $('input[name="nomor_surat"]').val(response.nomor_surat);
            $('input[name="waktu_mulai"]').val(response.waktu_mulai);
            $("#agenda-rapat").val(response.agenda_rapat);
            pimpinanRapatUsername = response.rapat_agenda_pimpinan.username;
            notulisRapatUsername = response.rapat_agenda_notulis.username;
            if (response.waktu_selesai !== null) {
                waktuSelesai.show();
                selectWaktuSelesai.val("manual");
                $('input[name="waktu_selesai"]').val(response.waktu_selesai);
            } else {
                selectWaktuSelesai.val("selesai");
            }
            if (response.tempat === "zoom") {
                selectPilihanTempat.val("zoom");
            }
            if (response.tempat !== "zoom") {
                tempatRapatGroup.show();
                selectPilihanTempat.val("custom");
                tempatRapat.val(response.tempat);
                tempatRapat.show();
            }
            if (response.kepanitiaan_id !== null) {
                $("#kepanitiaan").val(response.kepanitiaan_id);
            }
            if (response.rapat_kepanitiaan !== null) {
                pesertaKepanitiaan = response.rapat_kepanitiaan.pegawai.map(
                    (pegawai) => pegawai.username
                );
            }
            pesertaManual = response.rapat_agenda_peserta
                .filter(
                    (peserta) => !pesertaKepanitiaan.includes(peserta.username)
                )
                .map((peserta) => peserta.username);

            console.log(pesertaManual);

            pesertaRapat = [
                ...new Set([...pesertaManual, ...pesertaKepanitiaan]),
            ];

            tablePesertaRapat.ajax.reload();
            tablePimpinanRapat.ajax.reload();
            tableNotulisRapat.ajax.reload();
        },
        error: function (xhr) {
            console.log(xhr);
        },
    });
});
