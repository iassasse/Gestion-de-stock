<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['li_ref', 'material_id', 'espace_id'];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function espace(): BelongsTo
    {
        return $this->belongsTo(Espace::class);
    }
}
