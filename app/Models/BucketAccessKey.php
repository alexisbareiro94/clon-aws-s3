<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BucketAccessKey extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'access_key',
        'secret_hash',
        'abilities',
        'last_used_at',
        'expires_at',
        'revoked_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
