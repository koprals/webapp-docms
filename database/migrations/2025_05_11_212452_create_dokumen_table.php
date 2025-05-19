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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id('id_dokumen');

            // Foreign keys
            $table->foreignId('id_permohonan')->constrained('permohonan', 'id_permohonan');
            $table->foreignId('id_jenis')->constrained('jenis_dokumen', 'id_jenis');

            // Data dokumen
            $table->string('source', 1115); // Path/lokasi file
            $table->tinyInteger('status')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
