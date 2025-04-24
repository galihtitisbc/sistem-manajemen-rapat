<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Rapat\Http\Helper\StatusPesertaRapat;

class RapatPeserta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapat_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_agenda_id')->constrained('rapat_agendas');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', [StatusPesertaRapat::BERSEDIA->value, StatusPesertaRapat::TIDAK_BERSEDIA->value, StatusPesertaRapat::HADIR->value, StatusPesertaRapat::TIDAK_HADIR->value, StatusPesertaRapat::MENUNGGU->value])->default(StatusPesertaRapat::MENUNGGU->value);
            $table->boolean('is_penugasan')->default(false);
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
        Schema::dropIfExists('rapat_pesertas');
    }
}
