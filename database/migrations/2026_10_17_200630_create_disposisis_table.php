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
    Schema::create('disposisis', function (Blueprint $table) {
        $table->id();

        $table->foreignId('surat_masuk_id')
            ->constrained('surat_masuks')
            ->cascadeOnDelete();

        $table->foreignId('penerima_id')
            ->constrained('users')
            ->restrictOnDelete();

        $table->foreignId('pemberi_id')
            ->constrained('users')
            ->restrictOnDelete();

        $table->text('instruksi');
        $table->date('batas_waktu')->nullable();

        $table->string('status')->default('baru');
        $table->text('catatan_hasil')->nullable();
        $table->timestamp('selesai_pada')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('disposisis');
}

};
