<?php

namespace App\Domain;

use App\Core\Domain\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseEntity
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug'
    ];

    protected static function boot() {
        parent::boot();

        static::deleting(function($role) {
            $role->users()->delete();
        });
    }

    protected $dates = ['deleted_at'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
