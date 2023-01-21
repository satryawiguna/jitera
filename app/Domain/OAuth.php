<?php

namespace App\Domain;

use App\Core\Domain\BaseEntity;

class OAuth extends BaseEntity
{
    protected $table = 'oauth_access_tokens';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
