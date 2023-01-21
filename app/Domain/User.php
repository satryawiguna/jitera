<?php

namespace App\Domain;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Core\Domain\BaseAuthEntity;
use App\Core\Domain\Contract\IAggregateRoot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends BaseAuthEntity implements IAggregateRoot, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'created_by',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $keyType = 'string';

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->id = Str::uuid(36);
        });

        static::deleting(function($user) {
            $user->contact()->delete();
        });
    }

    public function contact()
    {
        return $this->morphOne(Contact::class, 'contactable');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function followed()
    {
        return $this->belongsToMany(self::class,
            'follows',
            'followed_id',
            'follower_id')
            ->withTimestamps()
            ->withPivot('created_at');
    }

    public function follower()
    {
        return $this->belongsToMany(self::class,
            'follows',
            'follower_id',
            'followed_id')
            ->withTimestamps()
            ->withPivot('created_at');
    }

    public function oAuth()
    {
        return $this->hasMany(OAuth::class, 'user_id');
    }
}
