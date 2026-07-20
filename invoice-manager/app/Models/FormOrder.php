<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'created_by',
        'invoice_id',
        'nomor',
        'nomor_urut',
        'tahun',
        'bulan',
        'tanggal_order',
        'nama_klien',
        'lokasi_project',
        'jenis_pekerjaan',
        'ukuran_bangunan',
        'arah_mata_angin',
        'share_location',
        'lingkup_pekerjaan',
        'catatan_klien',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_order' => 'date',
            'lingkup_pekerjaan' => 'array',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(FormOrderImage::class)->orderBy('urutan');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(FormOrderRevision::class)->orderBy('urutan');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */

    public function getIsLockedAttribute(): bool
    {
        return $this->status === 'selesai';
    }
}
