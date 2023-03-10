<?php

namespace App\Domain;

use App\Core\Domain\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Contact extends BaseEntity
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contactable_id',
        'contactable_type',
        'full_name',
        'nick_name',
        'country',
        'state',
        'city',
        'address',
        'post_code',
        'mobile'
    ];

    protected $keyType = 'string';

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public static function boot(){
        parent::boot();

        static::creating(function ($contact) {
            $contact->id = Str::uuid(36);
        });
    }

    public function contactable()
    {
        return $this->morphTo();
    }
}
