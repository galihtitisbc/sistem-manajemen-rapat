$(document).ready(function () {
    console.log(rapat);
    // untuk menampilkan data form edit dari controller

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
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
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

    $("#form-update-rapat").submit(function (e) {
        e.preventDefault();
        let pilihanTempat = $("#pilihan-tempat").val();
        waktuMulai = formatDateTimeLocalToYMDHIS($("#waktu-mulai").val());
        waktuSelesai = formatDateTimeLocalToYMDHIS($("#waktu-selesai").val());
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
            url: `/rapat/agenda-rapat/${slug}/update`,
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                "X-HTTP-Method-Override": "PUT",
            },
            success: function (response) {
                console.log(response);

                $(".invalid-feedback").text("");
                $("input, select, textarea").removeClass("is-invalid");
                $("#form-errors-list").hide();
                // Swal.fire({
                //     title: `${response.title}`,
                //     text: `${response.message}`,
                //     icon: `${response.icon}`,
                // });
                // setTimeout(() => {
                //     window.location.href = "/rapat/agenda-rapat";
                // }, 2000);
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                console.log(xhr);

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
                Swal.fire({
                    title: `Gagal`,
                    text: `${xhr.responseJSON.message}`,
                    icon: `error`,
                });
            },
        });
    });
});
function formatDateTimeLocalToYMDHIS(value) {
    if (!value) return "";
    const [date, time] = value.split("T");
    return `${date} ${time}:00`;
}
