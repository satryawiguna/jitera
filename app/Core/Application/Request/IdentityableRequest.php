<?php

namespace App\Core\Application\Request;

abstract class IdentityableRequest extends AuditableRequest
{
    public int|string $id;

    /**
     * @return int|string
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * @param int|string $id
     */
    public function setId(int|string $id): void
    {
        $this->id = $id;
    }
}
