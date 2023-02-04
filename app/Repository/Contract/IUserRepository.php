<?php

namespace App\Repository\Contract;

use App\Application\Request\Auth\RegisterDataRequest;
use App\Application\Request\CreateUserDataRequest;
use App\Application\Request\UpdateUserDataRequest;
use App\Core\Domain\BaseEntity;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

interface IUserRepository
{
    public function register(RegisterDataRequest $request): BaseEntity;

    public function revokeToken(string $email): BaseEntity|null;

    // array $related = ["contacts","so on..."]
    // array $args = ["status" => 1, "so on..." => "..."]
    public function all(string $order = "id", string $sort = "asc", array $args = [], array $related = []): Collection;

    // array $related = ["contacts","so on..."]
    // array $args = ["status" => 1, "so on..." => "..."]
    public function allSearch(string $keyword = "", string $order = "id", string $sort = "asc", array $args = [], array $related = []): Collection;

    // array $related = ["contacts","so on..."]
    // array $args = ["status" => 1, "so on..." => "..."]
    public function allSearchPage(string $keyword = "", int $perPage = 10, int $page = 1, string $order = "id", string $sort = "asc", array $args = [], array $related = []): Paginator;

    public function save(CreateUserDataRequest $request): BaseEntity;

    public function update(UpdateUserDataRequest $request): BaseEntity|null;

    public function delete(string $id): string;
}
