<?php

namespace App\Presentation\Http\Controllers\Api\V1\Auth;

use App\Application\Request\Auth\LoginDataRequest;
use App\Application\Request\Auth\LogoutDataRequest;
use App\Application\Request\Auth\RegisterDataRequest;
use App\Presentation\Http\Controllers\Api\ApiBaseController;
use App\Service\Contract\IAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthController extends ApiBaseController
{
    public IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/register/member",
     *     tags={"General"},
     *     summary="Register",
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
     *             @OA\Examples(example="Register member", value={"type":"SUCCESS","code_status":200,"data":{"id":"b00ef6af-bde8-4185-a780-0a9dbd6d652e","username":"kaela","email":"kaela@freshcms.net","email_verified_at":null,"status":"active","created_by":"system","updated_by":null,"created_at":"2023-01-21T07:12:57.000000Z","updated_at":"2023-01-21T07:12:57.000000Z","deleted_at":null,"role_id":2}}, summary="Register member")
     *         )
     *     )
     * )
     */
    public function actionRegister(Request $request)
    {
        $registerDataRequest = $this->setRequestData($request, new RegisterDataRequest());
        $registerDataRequest->role_id = 2;

        $this->setRequestAuthor($registerDataRequest);

        $registerResponse = $this->authService->register($registerDataRequest);

        if ($registerResponse->isError()) {
            return $this->getErrorJsonResponse($registerResponse);
        }

        return $this->getObjectJsonResponse($registerResponse);
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Authentication"},
     *     summary="Login authentication",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="identity",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
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
     *             @OA\Examples(example="Login with email identity", value={"type":"SUCCESS","code_status":200,"data":{"full_name":null,"nick_name":null,"token":{"token_type":"Bearer","expires_in":86400,"access_token":"...","refresh_token":"..."}}}, summary="Login with email identity"),
     *             @OA\Examples(example="Login with username identity", value={"type":"SUCCESS","code_status":200,"data":{"full_name":null,"nick_name":null,"token":{"token_type":"Bearer","expires_in":86400,"access_token":"...","refresh_token":"..."}}}, summary="Login with username identity")
     *         )
     *     )
     * )
     */
    public function actionLogin(Request $request)
    {
        $loginDataRequest = $this->setRequestData($request, new LoginDataRequest());

        $loginResponse = $this->authService->login($loginDataRequest);

        if ($loginResponse->isError()) {
            return $this->getErrorJsonResponse($loginResponse);
        }

        Cookie::queue('refresh_token', $loginResponse->getDto()["token"]["refresh_token"], 60*24);

        return $this->getObjectJsonResponse($loginResponse);
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     tags={"Authentication"},
     *     summary="Logout authentication",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
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
     *             @OA\Examples(example="Logout", value={"type":"SUCCESS","code_status":200,"message":"Logout succeed"}, summary="Logout")
     *         )
     *     )
     * )
     */
    public function actionLogout(Request $request)
    {
        $logoutDataRequest = $this->setRequestData($request, new LogoutDataRequest());

        $logoutResponse = $this->authService->logout($logoutDataRequest);

        if ($logoutResponse->isError()) {
            return $this->getErrorJsonResponse($logoutResponse);
        }

        $request->user()->token()->revoke();
        Cookie::forget('refresh_token');

        return $this->getSuccessLatestJsonResponse($logoutResponse);
    }

    /**
     * @OA\Post(
     *     path="/auth/refresh-token",
     *     tags={"Authentication"},
     *     summary="Refresh token authentication",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="refresh_token",
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
     *             @OA\Examples(example="Login with email identity", value={"type":"SUCCESS","code_status":200,"data":{"full_name":null,"nick_name":null,"token":{"token_type":"Bearer","expires_in":86400,"access_token":"...","refresh_token":"..."}}}, summary="Refresh the access token")
     *         )
     *     )
     * )
     */
    public function actionRefreshToken(Request $request)
    {
        $refreshResponse = $this->authService->refreshToken($request->refresh_token);

        if ($refreshResponse->isError()) {
            return $this->getErrorJsonResponse($refreshResponse);
        }

        $request->user()->token()->revoke();
        Cookie::forget('refresh_token');

        Cookie::queue('refresh_token', $refreshResponse->getDto()["token"]["refresh_token"], 60*24);

        return $this->getObjectJsonResponse($refreshResponse);
    }
}
