<?php

namespace App\Core\Application\Response;

use Illuminate\Support\Collection;

class GenericListResponse extends BasicResponse
{
    public Collection $dtoList;

    public int $totalCount;

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getDtoList(): Collection
    {
        return $this->dtoList ?? new Collection();
    }
}
