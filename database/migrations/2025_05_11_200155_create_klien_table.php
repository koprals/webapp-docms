<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlienTable extends Migration
{
    public function up()
    {
        Schema::create('klien', function (Blueprint $table) {
            $table->id('id_klien');
            $table->string('nama_klien', 115);
            $table->string('email', 50)->unique();
            $table->string('no_telp', 15);
            $table->string('nik', 17)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            // Relasi one-to-one dengan users via email
            $table->foreign('email')
                  ->references('email')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('klien');
    }
}
