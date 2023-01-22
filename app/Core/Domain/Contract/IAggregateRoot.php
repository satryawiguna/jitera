<?php

namespace App\Core\Domain\Contract;

use DateTime;

interface IAggregateRoot
{
    public function getCreatedBy(): string;

    public function getCreatedAt(): DateTime;

    public function getUpdatedBy(): string;

    public function getUpdatedAt(): DateTime;
}
