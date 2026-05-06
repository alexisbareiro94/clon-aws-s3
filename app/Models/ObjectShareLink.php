<?php

namespace App\Models;

use App\Policies\ObjectPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(ObjectPolicy::class)]
class ObjectShareLink extends Model
{
    protected $fillable = [
        'object_id',
        'created_by_user_id',
        'token',
        'permission',
        'expires_at',
        'revoked_at',
        'download_limit',
        'download_count',
        'url',
    ];

    public function object()
    {
        return $this->belongsTo(Objecto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function downloadEvents()
    {
        return $this->hasMany(ObjectDownloadEvent::class);
    }
}
