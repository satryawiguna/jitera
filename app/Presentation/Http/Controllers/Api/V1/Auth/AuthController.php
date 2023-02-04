<?php

namespace App\Presentation\Http\Controllers\Api\V1\Auth;

use App\Application\Request\Auth\LoginDataRequest;
use App\Application\Request\Auth\RegisterDataRequest;
use App\Infrastructure\Transformer\Auth\RegisterTransformer;
use App\Presentation\Http\Controllers\Api\ApiBaseController;
use App\Service\Contract\IAuthService;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Illuminate\Http\Request;

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
     *     operationId="actionRegister",
     *     tags={"General"},
     *     summary="Register",
     *     description="Return register data",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterDataRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             anyOf={
     *                  @OA\Schema(
     *                      title="meta",
     *                      allOf={
     *                          @OA\Property(ref="#/components/schemas/MetaResponse")
     *                      }
     *                  ),
     *                  @OA\Schema(
     *                      title="data",
     *                      allOf={
     *                          @OA\Property(ref="#/components/schemas/RegisterDataResponse")
     *                      }
     *                  )
     *             },
     *             @OA\Examples(example="Success result", value={"data":{"full_name":"Spider Man","nick_name":"spider","email":"spider@gmail.com"},"meta":{"type":"SUCCESS","code_status":200}}, summary="Success result object.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *            @OA\Examples(example="Bad request result", value={"type":"ERROR","code_status":400,"messages":{"The full name field is required.","The username has already been taken.","The email has already been taken."}}, summary="Bad request result object.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error"
     *     )
     * )
     */
    public function actionRegister(Request $request)
    {
        $config = new AutoMapperConfig();
        $config
            ->registerMapping(\stdClass::class, RegisterDataRequest::class)
            ->reverseMap();

        $mapper = new AutoMapper($config);
        $registerDataRequest = $mapper->map((object) $request->all(), RegisterDataRequest::class);
        $registerDataRequest->role_id = 2;

        $this->setRequestAuthor($registerDataRequest);

        $registerResponse = $this->authService->register($registerDataRequest);

        if ($registerResponse->isError()) {
            return $this->getErrorJsonResponse($registerResponse);
        }

        return $this->getObjectJsonResponse($registerResponse, new RegisterTransformer());
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     description="Return login data",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/LoginDataRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             anyOf={
     *                  @OA\Schema(
     *                      title="meta",
     *                      allOf={
     *                          @OA\Property(ref="#/components/schemas/MetaResponse")
     *                      }
     *                  ),
     *                  @OA\Schema(
     *                      title="data",
     *                      allOf={
     *                          @OA\Property(ref="#/components/schemas/LoginDataResponse")
     *                      }
     *                  )
     *             },
     *             @OA\Examples(example="Success result", value={"meta":{"type":"SUCCESS","code_status":200},"data":{"full_name":"Angling Dharma","nick_name":"Dharma","token":{"token_type":"Bearer","expires_in":86400,"access_token":"<access_token>","refresh_token":"<refresh_token>"}}}, summary="Success result object.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *            @OA\Examples(example="Bad request result", value={"type":"ERROR","code_status":400,"messages":{"The identity field is required."}}, summary="Bad request result object.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error"
     *     )
     * )
     *
     * @OA\Schema(
     *      schema="LoginDataResponse",
     *      type="object",
     *      @OA\Property(
     *           property="nick_name",
     *           title="nick_name",
     *           example="Satrya",
     *           type="string"
     *      ),
     *      @OA\Property(
     *           property="full_name",
     *           title="full_name",
     *           example="Satrya Wiguna",
     *           type="string"
     *      ),
     *      @OA\Property(property="token", title="token", ref="#/components/schemas/TokenDataResponse")
     * )
     *
     * @OA\Schema(
     *      schema="TokenDataResponse",
     *      title="token",
     *      type="object",
     *      @OA\Property(
     *           property="token_type",
     *           title="token_type",
     *           example="Bearer",
     *           type="string"
     *      ),
     *      @OA\Property(
     *           property="expires_in",
     *           title="expires_in",
     *           example="86400",
     *           type="integer"
     *      ),
     *      @OA\Property(
     *           property="access_token",
     *           title="access_token",
     *           example="<access_token>",
     *           type="string"
     *      ),
     *      @OA\Property(
     *           property="refresh_token",
     *           title="refresh_token",
     *           example="<refresh_token>",
     *           type="string"
     *      )
     * )
     */
    public function actionLogin(Request $request)
    {
        $config = new AutoMapperConfig();
        $config
            ->registerMapping(\stdClass::class, LoginDataRequest::class)
            ->reverseMap();

        $mapper = new AutoMapper($config);
        $loginDataRequest = $mapper->map((object) $request->all(), LoginDataRequest::class);

        $loginResponse = $this->authService->login($loginDataRequest);

        if ($loginResponse->isError()) {
            return $this->getErrorJsonResponse($loginResponse);
        }

        if ($loginResponse->isInfo()) {
            return $this->getInfoJsonResponse($loginResponse);
        }

        return $this->getObjectJsonResponse($loginResponse)
                ->cookie('refresh_token', $loginResponse->getDto()["token"]["refresh_token"], 60*24);
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     tags={"Authentication"},
     *     summary="Logout",
     *     description="Return logout data",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LogoutDataRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             anyOf={
     *                  @OA\Schema(
     *                      title="meta",
     *                      allOf={
     *                          @OA\Property(ref="#/components/schemas/MetaResponse")
     *                      }
     *                  ),
     *                  @OA\Property(
     *                       property="message",
     *                       title="message",
     *                       example="Logout succeed",
     *                       type="string"
     *                  )
     *             },
     *             @OA\Examples(example="Success result", value={"meta":{"type":"SUCCESS","code_status":200},"message":"Logout succeed"}, summary="Success result object.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *            @OA\Examples(example="Unauthorized result", value={"message":"Unauthenticated."}, summary="Unauthorized result object.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *            @OA\Examples(example="Not found result", value={"meta":{"type":"ERROR","code_status":404},"messages":{"User not found"}}, summary="Not found result object.")
     *         )
     *     )
     * )
     *
     * @OA\Schema(
     *      schema="LogoutDataRequest",
     *      type="object",
     *      required={"email"},
     *      @OA\Property(
     *           property="email",
     *           title="email",
     *           example="satrya@freshcms.net",
     *           type="string"
     *      )
     * )
     */
    public function actionLogout(Request $request)
    {
        $logoutResponse = $this->authService->logout($request->email);

        if ($logoutResponse->isError()) {
            return $this->getErrorJsonResponse($logoutResponse);
        }

        $request->user()->token()->revoke();

        return $this->getSuccessLatestJsonResponse($logoutResponse)
            ->withoutCookie('refresh_token');
    }

    /**
     * @OA\Post(
     *     path="/auth/refresh-token",
     *     tags={"Authentication"},
     *     summary="Refresh token",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             anyOf={
     *                  @OA\Schema(
     *                      title="meta",
     *                      allOf={
     *                          @OA\Property(ref="#/components/schemas/MetaResponse")
     *                      }
     *                  ),
     *                  @OA\Schema(
     *                      title="data",
     *                      allOf={
     *                          @OA\Property(ref="#/components/schemas/LoginDataResponse")
     *                      }
     *                  )
     *             },
     *             @OA\Examples(example="Success result", value={"meta":{"type":"SUCCESS","code_status":200},"data":{"full_name":"Angling Dharma","nick_name":"Dharma","token":{"token_type":"Bearer","expires_in":86400,"access_token":"<access_token>","refresh_token":"<refresh_token>"}}}, summary="Success result object.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *            @OA\Examples(example="Unauthorized result", value={"meta":{"type":"INFO","code_status":401},"messages":{"The refresh token is invalid."}}, summary="Unauthorized result object.")
     *         )
     *     )
     * )
     *
     * @OA\Schema(
     *      schema="RefreshDataRequest",
     *      type="object",
     *      required={"refresh_token"},
     *      @OA\Property(
     *           property="refresh_token",
     *           title="refresh_token",
     *           example="<refresh_token>",
     *           type="string"
     *      )
     * )
     */
    public function actionRefreshToken(Request $request)
    {
        $refreshResponse = $this->authService->refreshToken($request->refresh_token);

        if ($refreshResponse->isInfo()) {
            return $this->getInfoJsonResponse($refreshResponse);
        }

        if ($refreshResponse->isError()) {
            return $this->getErrorJsonResponse($refreshResponse);
        }

        $request->user()->token()->revoke();

        return $this->getObjectJsonResponse($refreshResponse)
            ->cookie('refresh_token', $refreshResponse->getDto()["token"]["refresh_token"], 60*24);
    }
}
