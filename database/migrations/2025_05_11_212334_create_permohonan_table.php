<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permohonan', function (Blueprint $table) {
            $table->id('id_permohonan');

            // Foreign keys
            $table->foreignId('id_klien')->constrained('klien', 'id_klien');
            $table->foreignId('id_jenis_permohonan')->constrained('jenis_permohonan', 'id_jenis');

            // Data permohonan
            $table->date('tgl_input');
            $table->text('alamat_pihak_satu');
            $table->text('alamat_pihak_dua');
            $table->string('no_pbb', 55)->nullable();
            $table->string('no_settifikat', 55)->nullable();
            $table->integer('luas_tanah');
            $table->tinyInteger('status')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan');
    }
};
