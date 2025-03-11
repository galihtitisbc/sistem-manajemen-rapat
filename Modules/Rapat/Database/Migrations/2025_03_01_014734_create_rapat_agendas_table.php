<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRapatAgendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapat_agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            // ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('pimpinan_id')->constrained('users');
            $table->foreignId('notulis_id')->constrained('users');
            $table->string('nomor_surat');
            $table->string('slug');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->text('agenda_rapat');
            $table->string('tempat');
            $table->enum('status', ['CANCELED', 'SCHEDULED', 'COMPLETED', 'STARTED'])->default('SCHEDULED');
            $table->string('lampiran')->nullable();
            $table->text('zoom_link')->nullable();
            $table->text('calendar_link')->nullable();
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
        Schema::dropIfExists('rapat_agendas');
    }
}
