<?php

namespace App\Presentation\Http\Controllers\Api\V1;

use App\Application\Request\CreateUserDataRequest;
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

    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"User"},
     *     summary="Get all users",
     *     @OA\Parameter(
     *          parameter="orderBy",
     *          in="query",
     *          name="orderBy",
     *          description="Column name that would be order",
     *          @OA\Schema(
     *              type="string",
     *              default="id"
     *          )
     *     ),
     *     @OA\Parameter(
     *          parameter="sort",
     *          in="query",
     *          name="sort",
     *          description="Direction of order by",
     *          @OA\Schema(
     *              type="string",
     *              default="ASC"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/ListSearchJSONResponse")
     *             },
     *             @OA\Examples(example="Get all users", value={"type":"SUCCESS","code_status":200,"datas":{{"id":"043acf25-9a28-4cc8-99d5-9cb6b29d4836","username":"betty.predovic","email":"aron98@example.com","email_verified_at":"2023-01-21T04:56:18.000000Z","status":"active","created_by":"system","updated_by":null,"created_at":"2023-01-21T04:56:18.000000Z","updated_at":"2023-01-21T04:56:18.000000Z","deleted_at":null,"role_id":2,"follower_count":0,"followed_count":0,"contact":{"id":"6adf2e69-e42e-4486-a484-57fa2e2627d8","contactable_type":"App\\Domain\\User","contactable_id":"043acf25-9a28-4cc8-99d5-9cb6b29d4836","nick_name":"Wyatt","full_name":"Hailey Schmeler","country":"Malta","state":"Mr. Johnnie Von","city":"Greggstad","address":"172 Bradtke Springs Suite 947","post_code":"30129","mobile":"281.257.4757","created_at":"2023-01-21T04:56:18.000000Z","updated_at":"2023-01-21T04:56:18.000000Z","deleted_at":null}}}}, summary="Get all users")
     *         )
     *     )
     * )
     */
    public function actionAll(Request $request) {
        $dataRequest = $this->setRequestData($request, new DataRequest());

        $users = $this->userService->getUserAll($dataRequest);

        if ($users->isError()) {
            return $this->getErrorJsonResponse($users);
        }

        return $this->getListJsonResponse($users);
    }

    /**
     * @OA\Post(
     *     path="/users/search",
     *     tags={"User"},
     *     summary="Get all users by search",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="search",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="orderBy",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="sort",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="args",
     *                     type="array",
     *                     example={"role"},
     *                     @OA\Items(
     *                          type="string",
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/ListSearchJSONResponse")
     *             },
     *             @OA\Examples(example="Get all users by search", value={"type":"SUCCESS","code_status":200,"total_count":10,"datas":{{"id":"043acf25-9a28-4cc8-99d5-9cb6b29d4836","username":"betty.predovic","email":"aron98@example.com","email_verified_at":"2023-01-21T04:56:18.000000Z","status":"active","created_by":"system","updated_by":null,"created_at":"2023-01-21T04:56:18.000000Z","updated_at":"2023-01-21T04:56:18.000000Z","deleted_at":null,"role_id":2,"follower_count":0,"followed_count":0,"contact":{"id":"6adf2e69-e42e-4486-a484-57fa2e2627d8","contactable_type":"App\\Domain\\User","contactable_id":"043acf25-9a28-4cc8-99d5-9cb6b29d4836","nick_name":"Wyatt","full_name":"Hailey Schmeler","country":"Malta","state":"Mr. Johnnie Von","city":"Greggstad","address":"172 Bradtke Springs Suite 947","post_code":"30129","mobile":"281.257.4757","created_at":"2023-01-21T04:56:18.000000Z","updated_at":"2023-01-21T04:56:18.000000Z","deleted_at":null}}}}, summary="Get all users by search")
     *         )
     *     )
     * )
     */
    public function actionSearch(Request $request) {
        $searchDataRequest = $this->setRequestData($request, new SearchDataRequest());

        $users = $this->userService->getUserSearch($searchDataRequest);

        if ($users->isError()) {
            return $this->getErrorJsonResponse($users);
        }

        return $this->getListSearchJsonResponse($users);
    }

    /**
     * @OA\Post(
     *     path="/users/search/page",
     *     tags={"User"},
     *     summary="Get all users by search & page",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="search",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="orderBy",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="sort",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="perPage",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="page",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="args",
     *                     type="array",
     *                     example={"role"},
     *                     @OA\Items(
     *                          type="string",
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/ListSearchPageJSONResponse")
     *             },
     *             @OA\Examples(example="Get all users by search & page", value={"type":"SUCCESS","code_status":200,"total_count":2,"meta":{"perPage":2,"currentPage":1},"datas":{{"id":"043acf25-9a28-4cc8-99d5-9cb6b29d4836","username":"betty.predovic","email":"aron98@example.com","email_verified_at":"2023-01-21T04:56:18.000000Z","status":"active","created_by":"system","updated_by":null,"created_at":"2023-01-21T04:56:18.000000Z","updated_at":"2023-01-21T04:56:18.000000Z","deleted_at":null,"role_id":2,"follower_count":0,"followed_count":0,"contact":{"id":"6adf2e69-e42e-4486-a484-57fa2e2627d8","contactable_type":"App\\Domain\\User","contactable_id":"043acf25-9a28-4cc8-99d5-9cb6b29d4836","nick_name":"Wyatt","full_name":"Hailey Schmeler","country":"Malta","state":"Mr. Johnnie Von","city":"Greggstad","address":"172 Bradtke Springs Suite 947","post_code":"30129","mobile":"281.257.4757","created_at":"2023-01-21T04:56:18.000000Z","updated_at":"2023-01-21T04:56:18.000000Z","deleted_at":null}}}}, summary="Get all users by search & page")
     *         )
     *     )
     * )
     */
    public function actionSearchPage(Request $request) {
        $searchPageDataRequest = $this->setRequestData($request, new SearchPageDataRequest());

        $users = $this->userService->getUserSearchPage($searchPageDataRequest);

        if ($users->isError()) {
            return $this->getErrorJsonResponse($users);
        }

        return $this->getListSearchPageJsonResponse($users);
    }

    /**
     * @OA\Get(
     *     path="/user/{id}",
     *     tags={"User"},
     *     summary="Get user",
     *     @OA\Parameter(
     *          parameter="id",
     *          in="query",
     *          name="id",
     *          description="User ID",
     *          @OA\Schema(
     *              type="string",
     *              default="id"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/ObjectJSONResponse")
     *             },
     *             @OA\Examples(example="Get user by ID", value={"type":"SUCCESS","code_status":200,"data":{"id":"8dee4369-1da2-430e-a571-b14274fef160","username":"erna","email":"erna@freshcms.net","email_verified_at":null,"status":"active","created_by":"system","updated_by":null,"created_at":"2023-01-19T19:25:19.000000Z","updated_at":"2023-01-19T19:25:19.000000Z","deleted_at":null,"role_id":2,"contact":null}}, summary="Get user by ID")
     *         )
     *     )
     * )
     */
    public function actionShow(string $id) {
        $user = $this->userService->getUser($id);

        if ($user->isError()) {
            return $this->getErrorJsonResponse($user);
        }

        return $this->getObjectJsonResponse($user);
    }

    /**
     * @OA\Post(
     *     path="/user",
     *     tags={"User"},
     *     summary="Store user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="full_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="nick_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirm",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="role_id",
     *                     type="int"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/ObjectJSONResponse")
     *             },
     *             @OA\Examples(example="Store user", value={"type":"SUCCESS","code_status":200,"data":{"id":"612774ad-c312-4185-bcae-030ffd0f6976","username":"riana","email":"riana@freshcms.net","email_verified_at":null,"status":"active","created_by":"admin","updated_by":null,"created_at":"2023-01-20T08:26:21.000000Z","updated_at":"2023-01-20T08:26:21.000000Z","deleted_at":null,"role_id":2}}, summary="Store user")
     *         )
     *     )
     * )
     */
    public function actionStore(Request $request) {
        $createUserDataRequest = $this->setRequestData($request, new CreateUserDataRequest());

        $this->setRequestAuthor($createUserDataRequest);

        $createUserResponse = $this->userService->storeUser($createUserDataRequest);

        if ($createUserResponse->isError()) {
            return $this->getErrorJsonResponse($createUserResponse);
        }

        return $this->getObjectJsonResponse($createUserResponse);
    }

    /**
     * @OA\Put(
     *     path="/user",
     *     tags={"User"},
     *     summary="Update user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="full_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="nick_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="country",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="state",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="city",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="post_code",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirm",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/ObjectJSONResponse")
     *             },
     *             @OA\Examples(example="Update user", value={"type":"SUCCESS","code_status":200,"data":{"id":"612774ad-c312-4185-bcae-030ffd0f6976","username":"riana","email":"riana@freshcms.net","email_verified_at":null,"status":"active","created_by":"admin","updated_by":null,"created_at":"2023-01-20T08:26:21.000000Z","updated_at":"2023-01-20T08:26:21.000000Z","deleted_at":null,"role_id":2}}, summary="Update user")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/user/{id}",
     *     tags={"User"},
     *     summary="Destroy user",
     *     @OA\Parameter(
     *          parameter="id",
     *          in="query",
     *          name="id",
     *          description="User ID",
     *          @OA\Schema(
     *              type="string",
     *              default="612774ad-c312-4185-bcae-030ffd0f6976"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/ObjectJSONResponse")
     *             },
     *             @OA\Examples(example="Destroy user", value={"type":"SUCCESS","code_status":200,"message":"Destroy ip address 8dee4369-1da2-430e-a571-b14274fef160 succeed"}, summary="Destroy user")
     *         )
     *     )
     * )
     */
    public function actionDestroy(string $id) {
        $destroyUserResponse = $this->userService->destroyUser($id);

        if ($destroyUserResponse->isError()) {
            return $this->getErrorJsonResponse($destroyUserResponse);
        }

        return $this->getSuccessLatestJsonResponse($destroyUserResponse);
    }

    /**
     * @OA\Get(
     *     path="/user/follow/{id}",
     *     tags={"User"},
     *     summary="Follow/unfollow user",
     *     @OA\Parameter(
     *          parameter="id",
     *          in="query",
     *          name="id",
     *          description="User ID",
     *          @OA\Schema(
     *              type="string",
     *              default="612774ad-c312-4185-bcae-030ffd0f6976"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/ObjectJSONResponse")
     *             },
     *             @OA\Examples(example="Follow/unfollow user", value={"type":"SUCCESS","code_status":200,"message":"Followed User 043acf25-9a28-4cc8-99d5-9cb6b29d4836 succeed"}, summary="Follow/unfollow user")
     *         )
     *     )
     * )
     */
    public function actionFollow(string $userId) {
        $followUnFollowUserResponse = $this->userService->followUnFollowUser($userId);

        if ($followUnFollowUserResponse->isError()) {
            return $this->getErrorJsonResponse($followUnFollowUserResponse);
        }

        return $this->getSuccessLatestJsonResponse($followUnFollowUserResponse);
    }
}
