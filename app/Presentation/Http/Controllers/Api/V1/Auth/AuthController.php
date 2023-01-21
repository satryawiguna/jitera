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
     *          response=200,
     *          description="Loged in"
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
