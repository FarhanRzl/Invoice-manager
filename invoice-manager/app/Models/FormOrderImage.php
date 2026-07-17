<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormOrderImage extends Model
{
    protected $fillable = [
        'form_order_id',
        'path',
        'caption',
        'urutan',
    ];

    public function formOrder(): BelongsTo
    {
        return $this->belongsTo(FormOrder::class);
    }
}
