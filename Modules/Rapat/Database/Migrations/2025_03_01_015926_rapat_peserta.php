<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->enum('status', ['BERSEDIA', 'TIDAK BERSEDIA', 'HADIR', 'TIDAK HADIR', 'MENUNGGU'])->default('MENUNGGU');
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
