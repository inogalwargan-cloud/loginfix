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
    Schema::create('surat_keluars', function (Blueprint $table) {
        $table->id();

        $table->string('nomor_agenda')->unique();
        $table->string('nomor_surat')->nullable();

        $table->date('tanggal_surat');
        $table->string('tujuan');
        $table->string('perihal');
        $table->text('isi_surat')->nullable();

        $table->string('sifat')->default('biasa');

        $table->string('status')->default('draft');

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
        Schema::dropIfExists('surat_keluars');
    }
};
