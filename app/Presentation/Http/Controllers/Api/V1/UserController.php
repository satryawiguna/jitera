<?php

namespace App\Presentation\Http\Controllers\Api\V1;

use App\Application\Request\CreateUserDataRequest;
use App\Application\Request\FollowDataRequest;
use App\Application\Request\UpdateUserDataRequest;
use App\Core\Application\Request\DataRequest;
use App\Core\Application\Request\SearchDataRequest;
use App\Core\Application\Request\SearchPageDataRequest;
use App\Presentation\Http\Controllers\Api\ApiBaseController;
use App\Service\Contract\IUserService;
use Illuminate\Http\Request;

class UserController extends ApiBaseController
{
    public IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function actionAll(Request $request) {
        $dataRequest = $this->setRequestData($request, new DataRequest());

        $users = $this->userService->getUserAll($dataRequest);

        if ($users->isError()) {
            return $this->getErrorJsonResponse($users);
        }

        return $this->getListJsonResponse($users);
    }

    public function actionSearch(Request $request) {
        $searchDataRequest = $this->setRequestData($request, new SearchDataRequest());

        $users = $this->userService->getUserSearch($searchDataRequest);

        if ($users->isError()) {
            return $this->getErrorJsonResponse($users);
        }

        return $this->getListSearchJsonResponse($users);
    }

    public function actionSearchPage(Request $request, $perPage, $page) {
        $searchPageRequest = new SearchPageDataRequest();
        $searchPageRequest->setSearch((string) $request->input("search"));
        $searchPageRequest->setPerPage($perPage);
        $searchPageRequest->setPage($page);

        $ipAddresses = $this->manageService->getIpAddressSearchPage($searchPageRequest);

        if ($ipAddresses->isError()) {
            return $this->getErrorJsonResponse($ipAddresses);
        }

        return $this->getListSearchPageJsonResponse($ipAddresses);
    }

    public function actionShow(string $id) {
        $user = $this->userService->getUser($id);

        if ($user->isError()) {
            return $this->getErrorJsonResponse($user);
        }

        return $this->getObjectJsonResponse($user);
    }

    public function actionStore(Request $request) {
        $createUserDataRequest = $this->setRequestData($request, new CreateUserDataRequest());

        $this->setRequestAuthor($createUserDataRequest);

        $createUserResponse = $this->userService->storeUser($createUserDataRequest);

        if ($createUserResponse->isError()) {
            return $this->getErrorJsonResponse($createUserResponse);
        }

        return $this->getObjectJsonResponse($createUserResponse);
    }

    public function actionUpdate(Request $request, string $id) {
        $updateUserDataRequest = $this->setRequestData($request, new UpdateUserDataRequest());
        $updateUserDataRequest->id = $id;

        $this->setRequestAuthor($updateUserDataRequest);

        $updateUserResponse = $this->userService->updateUser($updateUserDataRequest);

        if ($updateUserResponse->isError()) {
            return $this->getErrorJsonResponse($updateUserResponse);
        }

        return $this->getObjectJsonResponse($updateUserResponse);
    }

    public function actionDestroy(string $id) {
        $destroyUserResponse = $this->userService->destroyUser($id);

        if ($destroyUserResponse->isError()) {
            return $this->getErrorJsonResponse($destroyUserResponse);
        }

        return $this->getSuccessLatestJsonResponse($destroyUserResponse);
    }

    public function actionFollow(string $userId) {
        $followUnFollowUserResponse = $this->userService->followUnFollowUser($userId);

        if ($followUnFollowUserResponse->isError()) {
            return $this->getErrorJsonResponse($followUnFollowUserResponse);
        }

        return $this->getSuccessLatestJsonResponse($followUnFollowUserResponse);
    }
}
