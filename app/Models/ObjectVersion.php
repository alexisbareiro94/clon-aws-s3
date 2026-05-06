<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjectVersion extends Model
{
    protected $fillable = [
        'object_id',
        'version_number',
        'storage_path',
        'mime_type',
        'size_bytes',
        'checksum',
        'is_current',
        'metadata',
    ];

    public function object()
    {
        return $this->belongsTo(Objecto::class);
    }
}
