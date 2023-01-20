<?php

namespace App\Application\Request\Auth;

use App\Core\Domain\BrokenRule;

class LogoutDataRequest
{
    use BrokenRule;

    public string $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
