<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function buckets()
    {
        return $this->hasMany(Bucket::class);
    }

    public function objects()
    {
        return $this->hasMany(Objecto::class);
    }

    public function accessKeys()
    {
        return $this->hasMany(BucketAccessKey::class);
    }

    public function shareLinks()
    {
        return $this->hasMany(ObjectShareLink::class);
    }

    public function downloadEvents()
    {
        return $this->hasMany(ObjectDownloadEvent::class);
    }
}
