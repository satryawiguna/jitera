<?php

namespace App\Core\Application\Response;

use Illuminate\Support\Collection;

class GenericListResponse extends BasicResponse
{
    public Collection $dtoList;

    public function getDtoList(): Collection
    {
        return $this->dtoList ?? new Collection();
    }
}
