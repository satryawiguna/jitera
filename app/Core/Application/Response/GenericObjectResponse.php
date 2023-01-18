<?php

namespace App\Core\Application\Response;

class GenericObjectResponse extends BasicResponse
{
    public $dto;

    public function getDto()
    {
        return $this->dto ?? new \stdClass();
    }
}
