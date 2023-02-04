<?php

namespace App\Service\Contract;

use App\Application\Request\CreateUserDataRequest;
use App\Application\Request\UpdateUserDataRequest;
use App\Core\Application\Request\DataRequest;
use App\Core\Application\Request\ListDataRequest;
use App\Core\Application\Request\ListSearchDataRequest;
use App\Core\Application\Request\ListSearchPageDataRequest;
use App\Core\Application\Request\SearchDataRequest;
use App\Core\Application\Request\SearchPageDataRequest;
use App\Core\Application\Response\BasicResponse;
use App\Core\Application\Response\GenericListResponse;
use App\Core\Application\Response\GenericListSearchPageResponse;
use App\Core\Application\Response\GenericListSearchResponse;
use App\Core\Application\Response\GenericObjectResponse;

interface IUserService
{
    public function getUserAll(ListDataRequest $request): GenericListResponse;

    public function getUserSearch(ListSearchDataRequest $request): GenericListSearchResponse;

    public function getUserSearchPage(ListSearchPageDataRequest $request): GenericListSearchPageResponse;

    public function getUser(string $id): GenericObjectResponse;

    public function storeUser(CreateUserDataRequest $request): GenericObjectResponse;

    public function updateUser(UpdateUserDataRequest $request): GenericObjectResponse;

    public function destroyUser(string $id): BasicResponse;

    public function followUnFollowUser(string$id): BasicResponse;
}
