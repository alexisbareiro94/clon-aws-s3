<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Bucket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'visibility',
        'description',
        'max_size_bytes',
        'settings',
    ];

    // Auto-generate slug from name before creating
    protected static function booted(): void
    {
        static::creating(function (Bucket $bucket) {
            $bucket->slug ??= Str::slug($bucket->name);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function objectos()
    {
        return $this->hasMany(Objecto::class);
    }

    /** 'pr' => 'Privado', 'pu' => 'Público' */
    public function getVisibilityLabelAttribute(): string
    {
        return $this->visibility === 'pu' ? 'Público' : 'Privado';
    }
}
