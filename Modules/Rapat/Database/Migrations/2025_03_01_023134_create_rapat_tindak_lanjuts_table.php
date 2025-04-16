<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRapatTindakLanjutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapat_tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_agenda_id')->constrained('rapat_agendas');
            $table->foreignId('user_id')->constrained('users');
            $table->text('deskripsi_tugas');
            $table->text('slug');
            $table->date('batas_waktu');
            $table->enum('status', ['SELESAI', 'BELUM SELESAI'])->default('BELUM SELESAI');
            $table->string('tugas')->nullable();
            $table->enum('penilaian', ['MELEBIHI EKSPETASI', 'SESUAI EKSPETASI', "TIDAK SESUAI EKSPETASI", "BELUM DINILAI"])->default("BELUM DINILAI");
            $table->text('komentar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rapat_tindak_lanjuts');
    }
}
