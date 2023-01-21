<?php

namespace App\Service;

use App\Application\Exceptions\ResponseBadRequestException;
use App\Application\Exceptions\ResponseInvalidClientException;
use App\Application\Exceptions\ResponseInvalidLoginAttemptException;
use App\Application\Exceptions\ResponseNotFoundException;
use App\Application\Request\Auth\LoginDataRequest;
use App\Application\Request\Auth\LogoutDataRequest;
use App\Application\Request\Auth\RegisterDataRequest;
use App\Core\Application\Response\BasicResponse;
use App\Core\Application\Response\GenericObjectResponse;
use App\Core\Application\Response\HttpResponseType;
use App\Repository\Contract\IUserRepository;
use App\Service\Contract\IAuthService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Client as OClient;

class AuthService extends BaseService implements IAuthService
{
    public IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterDataRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        DB::beginTransaction();

        try {
            $brokenRules = $request->getBrokenRules([
                'nick_name' => 'required|string',
                'full_name' => 'required|string',
                'username' => 'required|string|unique:users',
                'email' => 'required|string|unique:users|email',
                'password' => 'required|string|min:8',
                'password_confirm' => 'required|same:password'
            ]);

            if ($brokenRules->fails()) {
                throw new ResponseBadRequestException($brokenRules->errors()->getMessages());
            }

            $register = $this->userRepository->register($request);

            $response = $this->setGenericObjectResponse($response,
                $register,
                'SUCCESS',
                HttpResponseType::SUCCESS->value);

            Log::info("User register success");

        } catch (ResponseBadRequestException $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST->value,
                $ex->getMessages());

            Log::error("Invalid validation", $response->getMessageResponseError());

        } catch (QueryException $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST->value,
                $ex->getMessage());

            Log::error("Invalid validation", $response->getMessageResponseError());

        } catch (Exception $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                $ex->getMessage());

            Log::error("Invalid register", $response->getMessageResponseError());
        }

        DB::commit();

        return $response;
    }

    public function login(LoginDataRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            if (filter_var($request->identity, FILTER_VALIDATE_EMAIL)) {
                $rules = [
                    'identity' => 'required|email',
                    'password' => 'required'
                ];

                $identity = 'email';
            } else {
                $rules = [
                    'identity' => 'required',
                    'password' => 'required'
                ];

                $identity = 'username';
            }

            $brokenRules = $request->getBrokenRules($rules);

            if ($brokenRules->fails()) {
                throw new ResponseBadRequestException($brokenRules->errors()->getMessages());
            }

            if (!Auth::attempt([$identity => $request->identity, "password" => $request->password])) {
                throw new ResponseInvalidLoginAttemptException('Login invalid');
            }

            //Use this if we wan't to use refresh token
            //$user = Auth::user();
            //$token = $user->createToken('token')->accessToken;

            $oClient = new OClient();
            $oClient->setKeyType("string");
            $oClient = $oClient->where('password_client', 1)->first();

            $oauthResponse = Http::asForm()->post('jit_server/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => Auth::user()->email,
                'password' => $request->password,
                'scope' => '*',
            ]);

            if (array_key_exists("error", $oauthResponse->json())) {
                throw new ResponseInvalidClientException($oauthResponse->json()["message"]);
            }

            $token = $oauthResponse->json();

            $user = Auth::user();

            $login = [
                'full_name' => $user->full_name,
                'nick_name' => $user->nick_name,
                'token' => $token
            ];

            $response = $this->setGenericObjectResponse($response,
                $login,
                'SUCCESS',
                HttpResponseType::SUCCESS->value);

            Log::info("User $user->id: Login succeed");
        } catch (ResponseBadRequestException $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST->value,
                $ex->getMessages());

            Log::error("Invalid validation", $response->getMessageResponseError());
        } catch (ResponseInvalidLoginAttemptException $ex) {
            $response = $this->setMessageResponse($response,
                "ERROR",
                HttpResponseType::UNAUTHORIZED->value,
                $ex->getMessage());

            Log::error("Invalid auth attempt", [$response->getMessageResponseError()]);
        } catch (ResponseInvalidClientException $ex) {
            $response = $this->setMessageResponse($response,
                "ERROR",
                HttpResponseType::UNAUTHORIZED->value,
                $ex->getMessage());

            Log::error("Invalid client", [$response->getMessageResponseError()]);
        } catch (\Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::UNAUTHORIZED->value,
                $ex->getMessage());

            Log::error("Invalid login", [$response->getMessageResponseError()]);
        }

        return $response;
    }

    public function logout(LogoutDataRequest $request): BasicResponse
    {
        $response = new BasicResponse();

        try {
            $brokenRules = $request->getBrokenRules([
                'email' => 'required|email'
            ]);

            if ($brokenRules->fails()) {
                throw new ResponseBadRequestException($brokenRules->errors()->getMessages());
            }

            $user = $this->userRepository->revokeToken($request->email);

            if (!$user) {
                throw new ResponseNotFoundException('User not found');
            }

            $response = $this->setMessageResponse($response,
                'SUCCESS',
                HttpResponseType::SUCCESS->value,
                'Logout succeed');

            Log::info("User $user->id: Logout succeed");
        } catch (ResponseBadRequestException $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST->value,
                $ex->getMessages());

            Log::error("Invalid validation", $response->getMessageResponseError());
        } catch (ResponseNotFoundException $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND->value,
                $ex->getMessage());

            Log::error("Email not found", $response->getMessageResponseError());
        } catch (\Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                $ex->getMessage());

            Log::error("Invalid logout", $response->getMessageResponseError());
        }

        return $response;
    }

    public function refreshToken(string $token): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            $oClient = new OClient();
            $oClient->setKeyType("string");
            $oClient = $oClient->where('password_client', 1)->first();

            $oauthResponse = Http::asForm()->post('iam_server/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $token,
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'scope' => '*',
            ]);

            if (array_key_exists("error", $oauthResponse->json())) {
                throw new ResponseInvalidClientException($oauthResponse->json()["message"]);
            }

            $token = $oauthResponse->json();

            $refresh = [
                'token' => $token
            ];

            $response = $this->setGenericObjectResponse($response,
                $refresh,
                'SUCCESS',
                HttpResponseType::SUCCESS->value);

            Log::info("User ". Auth::user()->id .": Refresh token success");
        } catch (ResponseInvalidClientException $ex) {
            $response = $this->setMessageResponse($response,
                "ERROR",
                HttpResponseType::UNAUTHORIZED->value,
                $ex->getMessage());

            Log::error("Invalid client", [$response->getMessageResponseError()]);
        } catch (\Exception $ex) {
            $response = $this->setMessageResponse($response,
                "ERROR",
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                $ex->getMessage());

            Log::error("Invalid refresh token", [$response->getMessageResponseError()]);
        }

        return $response;
    }
}
