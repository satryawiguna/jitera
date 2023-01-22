<?php

namespace App\Core\Application\Request;

use App\Core\Domain\BrokenRule;
use Illuminate\Http\Request;

abstract class AuditableRequest
{
    use BrokenRule;

    public ?string $request_by;

    /**
     * @return string|null
     */
    public function getRequestBy(): ?string
    {
        return $this->request_by;
    }

    /**
     * @param string|null $request_by
     */
    public function setRequestBy(?string $request_by): void
    {
        $this->request_by = $request_by;
    }
}
