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
    Schema::create('surat_masuks', function (Blueprint $table) {
        $table->id();

        $table->string('nomor_agenda')->unique();
        $table->string('nomor_surat');
        $table->date('tanggal_surat');
        $table->date('tanggal_diterima');

        $table->string('pengirim');
        $table->string('perihal');
        $table->text('isi_ringkas')->nullable();

        $table->string('sifat')->default('biasa');
        $table->string('status')->default('baru');

        $table->string('file_path')->nullable();

        $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
