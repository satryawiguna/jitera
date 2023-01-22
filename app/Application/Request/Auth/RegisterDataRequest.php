<?php

namespace App\Application\Request\Auth;

use App\Core\Application\Request\AuditableRequest;

class RegisterDataRequest extends AuditableRequest
{
    public string $nick_name;

    public string $full_name;

    public string $username;

    public string $email;

    public string $password;

    public string $password_confirm;

    /**
     * @return string
     */
    public function getNickName(): string
    {
        return $this->nick_name;
    }

    /**
     * @param string $nick_name
     */
    public function setNickName(string $nick_name): void
    {
        $this->nick_name = $nick_name;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * @param string $full_name
     */
    public function setFullName(string $full_name): void
    {
        $this->full_name = $full_name;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

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

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPasswordConfirm(): string
    {
        return $this->password_confirm;
    }

    /**
     * @param string $password_confirm
     */
    public function setPasswordConfirm(string $password_confirm): void
    {
        $this->password_confirm = $password_confirm;
    }
}
