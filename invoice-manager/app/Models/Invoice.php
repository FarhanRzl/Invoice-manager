<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'created_by',
        'nomor',
        'nomor_urut',
        'tahun',
        'bulan',
        'klien',
        'alamat',
        'phone',
        'email',
        'tanggal',
        'jatuh_tempo',
        'desain_tema',
        'kop_config',
        'diskon_persen',
        'ppn_persen',
        'status',
        'tanggal_lunas',
        'catatan',
        'sph_config',
        'sign_config',
        'rekening_config',
        'qris_path',
        'printed_at',
        'termin_show_pct',
    ];

    protected function casts(): array
    {
        return [
            'kop_config' => 'array',
            'sph_config' => 'array',
            'sign_config' => 'array',
            'rekening_config' => 'array',

            'tanggal' => 'date',
            'jatuh_tempo' => 'date',
            'tanggal_lunas' => 'date',
            'printed_at' => 'datetime',

            'subtotal' => 'decimal:2',
            'diskon_persen' => 'decimal:2',
            'ppn_persen' => 'decimal:2',
            'total' => 'decimal:2',

            'termin_show_pct' => 'boolean',
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

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function terms(): HasMany
    {
        return $this->hasMany(InvoiceTerm::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeLunas(Builder $query): Builder
    {
        return $query->where('status', 'lunas');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */

    public function getIsLockedAttribute(): bool
    {
        return ! is_null($this->printed_at);
    }

    public function getThemeAttribute(): array
    {
        return config('invoice_themes.'.$this->desain_tema) ?? config('invoice_themes.classic');
    }

    public function getNomorKwitansiAttribute(): string
    {
        return str_replace('INV/', 'KWT/', $this->nomor);
    }
}