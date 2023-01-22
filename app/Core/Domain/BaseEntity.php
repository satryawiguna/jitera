<?php

namespace App\Core\Domain;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

abstract class BaseEntity extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getCreatedBy(): string
    {
        return $this->getAttribute("created_by");
    }

    public function getCreatedAt(): DateTime
    {
        return $this->getAttribute("created_at");
    }

    public function getUpdatedBy(): string
    {
        return $this->getAttribute("updated_by");
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->getAttribute("updated_by");
    }

    public function setCreatedInfo(string|null $created_by): void
    {
        $this->setAttribute("created_by", $created_by);
        $this->setAttribute("created_at", Carbon::now()->toDateTimeString());
    }

    public function setUpdatedInfo(string|null $updated_by): void
    {
        $this->setAttribute("updated_by", $updated_by);
        $this->setAttribute("updated_at", Carbon::now()->toDateTimeString());
    }
}
