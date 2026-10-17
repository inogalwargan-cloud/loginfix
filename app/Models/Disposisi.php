<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposisi extends Model
{
    protected $fillable = [
        'surat_masuk_id',
        'penerima_id',
        'pemberi_id',
        'instruksi',
        'batas_waktu',
        'status',
        'catatan_hasil',
        'selesai_pada',
    ];

    protected function casts(): array
    {
        return [
            'batas_waktu' => 'date',
            'selesai_pada' => 'datetime',
        ];
    }

    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    public function pemberi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pemberi_id');
    }
}
