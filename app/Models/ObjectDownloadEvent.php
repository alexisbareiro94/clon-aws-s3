<?php

namespace App\Models;

use App\Policies\ObjectPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(ObjectPolicy::class)]
class ObjectDownloadEvent extends Model
{
    protected $fillable = [
        'object_id',
        'share_link_id',
        'user_id',
        'ip_address',
        'country_code',
        'user_agent',
        'referrer',
    ];

    public function object()
    {
        return $this->belongsTo(Objecto::class, 'object_id');
    }

    public function shareLink()
    {
        return $this->belongsTo(ObjectShareLink::class, 'share_link_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
