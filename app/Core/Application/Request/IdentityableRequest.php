<?php

namespace App\Core\Application\Request;

abstract class IdentityableRequest extends AuditableRequest
{
    public int|string $id;
}
