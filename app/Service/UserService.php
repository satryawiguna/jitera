<?php

namespace App\Service;

use App\Application\Exceptions\ResponseBadRequestException;
use App\Application\Exceptions\ResponseInvalidUserIdFollowUnFollowException;
use App\Application\Exceptions\ResponseNotFoundException;
use App\Application\Request\CreateUserDataRequest;
use App\Application\Request\UpdateUserDataRequest;
use App\Core\Application\Request\ListDataRequest;
use App\Core\Application\Request\ListSearchDataRequest;
use App\Core\Application\Request\ListSearchPageDataRequest;
use App\Core\Application\Response\BasicResponse;
use App\Core\Application\Response\GenericListResponse;
use App\Core\Application\Response\GenericListSearchPageResponse;
use App\Core\Application\Response\GenericListSearchResponse;
use App\Core\Application\Response\GenericObjectResponse;
use App\Core\Application\Response\HttpResponseType;
use App\Repository\Contract\IUserRepository;
use App\Service\Contract\IUserService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UserService extends BaseService implements IUserService
{
    public IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserAll(ListDataRequest $request): GenericListResponse
    {
        $response = new GenericListResponse();

        try {
            $brokenRules = $request->validate();

            if ($brokenRules->fails()) {
                throw new ResponseBadRequestException($brokenRules->errors()->getMessages());
            }

            $users = $this->userRepository->all($request->order_by,
                $request->sort,
                $request->args,
                ['contact']);

            $response = $this->setGenericListResponse($response,
                $users,
                'SUCCESS',
                HttpResponseType::SUCCESS->value);

        } catch (ResponseBadRequestException $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST->value,
                $ex->getMessages());

            Log::error("Bad request to store user", $response->getMessageResponseError());

        } catch (Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                $ex->getMessage());

            Log::error("Invalid get user all", $response->getMessageResponseError());
        }

        return $response;
    }

    public function getUserSearch(ListSearchDataRequest $request): GenericListSearchResponse
    {
        $response = new GenericListSearchResponse();

        try {
            $brokenRules = $request->validate();

            if ($brokenRules->fails()) {
                throw new ResponseBadRequestException($brokenRules->errors()->getMessages());
            }

            $users = $this->userRepository->allSearch($request->search,
                $request->order_by,
                $request->sort,
                $request->args,
                ["contact"]
            );

            $response = $this->setGenericListSearchResponse($response,
                $users,
                $users->count(),
                'SUCCESS',
                HttpResponseType::SUCCESS->value);

        } catch (ResponseBadRequestException $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST->value,
                $ex->getMessages());

            Log::error("Bad request to store user", $response->getMessageResponseError());

        } catch (Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                $ex->getMessage());

            Log::error("Invalid get user by search", $response->getMessageResponseError());
        }

        return $response;
    }

    public function getUserSearchPage(ListSearchPageDataRequest $request): GenericListSearchPageResponse
    {
        $response = new GenericListSearchPageResponse();

        try {
            $users = $this->userRepository->allSearchPage($request->search,
                $request->per_page,
                $request->page,
                $request->order_by,
                $request->sort,
                $request->args,
                ["contact"]
            );

            $response = $this->setGenericListSearchPageResponse($response,
                $users->getCollection(),
                $users->count(),
                ["perPage" => $users->perPage(), "currentPage" => $users->currentPage()],
                'SUCCESS',
                HttpResponseType::SUCCESS->value);

        } catch (Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                $ex->getMessage());

            Log::error("Invalid get user by search page", $response->getMessageResponseError());
        }

        return $response;
    }

    public function getUser(string $id): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            $user = $this->userRepository->findById(id: $id,
                related: ["contact"]);

            if (!$user) {
                throw new ResponseNotFoundException("User not found");
            }

            $response = $this->setGenericObjectResponse($response,
                $user,
                'SUCCESS',
                HttpResponseType::SUCCESS->value);

        } catch (ResponseNotFoundException $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND->value,
                $ex->getMessage());

            Log::error("User $id not found", $response->getMessageResponseError());

        } catch (Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                $ex->getMessage());

            Log::error("Invalid get user $id", $response->getMessageResponseError());
        }

        return $response;
    }

    public function storeUser(CreateUserDataRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        DB::beginTransaction();

        try {
            $brokenRules = $request->getBrokenRules([
                'nick_name' => 'required|string',
                'full_name' => 'required|string',
                'country' => 'string',
                'state' => 'string',
                'city' => 'string',
                'address' => 'string',
                'post_code' => 'int',
                'mobile' => 'string',
                'username' => 'required|string|unique:users',
                'email' => 'required|string|unique:users|email',
                'password' => 'required|string|min:8',
                'password_confirm' => 'required|same:password',
                'role_id' => 'required|int'
            ]);

            if ($brokenRules->fails()) {
                throw new ResponseBadRequestException($brokenRules->errors()->getMessages());
            }

            $user = $this->userRepository->save($request);

            DB::commit();

            $response = $this->setGenericObjectResponse($response,
                $user,
                'SUCCESS',
                HttpResponseType::SUCCESS->value);

            Log::info("User store succeed");

        } catch (ResponseBadRequestException $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST->value,
                $ex->getMessages());

            Log::error("Bad request to store user", $response->getMessageResponseError());

        } catch(QueryException) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                'Duplicate entry detected');

            Log::error("Invalid query to store user", $response->getMessageResponseError());

        } catch (Exception $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                $ex->getMessage());

            Log::error("Invalid user store", $response->getMessageResponseError());
        }

        return $response;
    }

    public function updateUser(UpdateUserDataRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        DB::beginTransaction();

        try {
            $user = $this->userRepository->update($request);

            DB::commit();

            if (!$user) {
                throw new ResponseNotFoundException('User not found');
            }

            $response = $this->setGenericObjectResponse($response,
                $user,
                'SUCCESS',
                HttpResponseType::SUCCESS->value);

            Log::info("User update succeed");

        } catch(QueryException) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                'Duplicate entry detected');

            Log::error("Invalid query to update user", $response->getMessageResponseError());

        } catch (ResponseNotFoundException $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND->value,
                $ex->getMessage());

            Log::error("Invalid user not found", $response->getMessageResponseError());

        } catch (Exception $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                $ex->getMessage());

            Log::error("Invalid user update", $response->getMessageResponseError());
        }

        return $response;
    }

    public function destroyUser(string $id): BasicResponse
    {
        $response = new BasicResponse();

        DB::beginTransaction();

        try {
            $user = $this->userRepository->findById($id);

            if (!$user) {
                throw new ResponseNotFoundException('User not found');
            }

            $this->userRepository->delete($id);

            DB::commit();

            $response = $this->setMessageResponse($response,
                "SUCCESS",
                HttpResponseType::SUCCESS->value,
                'Destroy User ' . $user->id . ' succeed');

            Log::info("User $user->id: destroyed");
        } catch (ResponseNotFoundException $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND->value,
                $ex->getMessage());

            Log::error("Invalid user not found", $response->getMessageResponseError());
        } catch (\Exception $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                $ex->getMessage());

            Log::error("Invalid user destroy", $response->getMessageResponseError());
        }

        return $response;
    }

    public function followUnFollowUser(string $id): BasicResponse
    {
        $response = new BasicResponse();

        DB::beginTransaction();

        try {
            if (Auth::id() === $id) {
                throw new ResponseInvalidUserIdFollowUnFollowException('Could follow it self');
            }

            $followUnFollow = $this->userRepository->followUnFollow(Auth::id(), $id);

            DB::commit();

            $response = $this->setMessageResponse($response,
                "SUCCESS",
                HttpResponseType::SUCCESS->value,
                ($followUnFollow === 1) ? 'Followed User ' . $id . ' succeed' : 'Un Followed User ' . $id . ' succeed');

            Log::info("User $id followed by ". Auth::id());
        } catch(ResponseInvalidUserIdFollowUnFollowException $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST->value,
                $ex->getMessage());

            Log::error("Invalid followed user" . $id, $response->getMessageResponseError());

        } catch(\Exception $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR->value,
                $ex->getMessage());

            Log::error("Invalid followed user" . $id, $response->getMessageResponseError());
        }

        return $response;
    }
}
