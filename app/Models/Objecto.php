<?php

namespace App\Models;

use App\Policies\ObjectPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(ObjectPolicy::class)]
class Objecto extends Model
{
    use HasFactory;

    protected $table = 'objects';

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $appends = [
        'file_url',
    ];

    public function getFileUrlAttribute()
    {
        return route('object.view', [
            'bucket' => $this->bucket->slug,
            'objecto' => $this->original_name,
        ]);
    }

    protected $fillable = [
        'bucket_id',
        'user_id',
        'object_key',
        'original_name',
        'storage_disk',
        'storage_path',
        'mime_type',
        'size_bytes',
        'checksum',
        'visibility',
        'metadata',
    ];

    public function bucket()
    {
        return $this->belongsTo(Bucket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function versions()
    {
        return $this->hasMany(ObjectVersion::class, 'object_id');
    }

    public function shareLinks()
    {
        return $this->hasMany(ObjectShareLink::class, 'object_id');
    }

    public function downloadEvents()
    {
        return $this->hasMany(ObjectDownloadEvent::class, 'object_id');
    }

    public function scopeSearch($query, $q)
    {
        if (!$q) return $query;

        return $query->where(function ($query) use ($q) {
            $query->where('original_name', 'ilike', "%{$q}%")
                ->orWhere('mime_type', 'ilike', "%{$q}%");
        });
    }
}
