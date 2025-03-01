<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->foreignId('notulen_id')->constrained('users');
            $table->string('judul_rapat');
            $table->string('slug');
            $table->date('tanggal');
            $table->text('deskripsi');
            $table->string('tempat');
            $table->enum('status', ['CANCELED', 'SCHEDULED', 'COMPLETED'])->default('SCHEDULED');
            $table->string('lampiran');
            $table->string('zoom_link');
            $table->string('calendar_link');
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
