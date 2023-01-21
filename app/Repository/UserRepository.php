<?php

namespace App\Repository;

use App\Application\Request\Auth\RegisterDataRequest;
use App\Application\Request\CreateUserDataRequest;
use App\Application\Request\UpdateUserDataRequest;
use App\Core\Domain\BaseEntity;
use App\Domain\Contact;
use App\Domain\User;
use App\Repository\Contract\IUserRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements IUserRepository
{
    public User $user;

    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->user = $user;
    }

    public function register(RegisterDataRequest $request): BaseEntity
    {
        $user = new $this->user([
            "username" => $request->username,
            "email" => $request->email,
            "role_id" => 2
        ]);

        $this->setAuditableInformationFromRequest($user, $request);

        $user->setAttribute('password', bcrypt($request->password));

        $user->save();

        $user->contact()->save(new Contact([
            "full_name" => $request->full_name,
            "nick_name" => $request->nick_name,
        ]));

        return $user->fresh();
    }

    public function revokeToken(string $email): BaseEntity|null
    {
        $user = $this->user->where("email", $email)
            ->get();

        if ($user->count() < 1) {
            return null;
        }

        $user->last()->oAuth()->delete();

        return $user->last();
    }

    public function all(string $order = "id", string $sort = "asc", array $related = []): Collection {
        $model = $this->model;

        if (count($related) > 0) {
            $model = $model->with($related)
                        ->withCount(['follower', 'followed']);
        }

        return $model->orderBy($order, $sort)
            ->get();
    }

    public function allSearch(string $keyword = "", string $order = "id", string $sort = "asc",
                              array $args = [], array $related = []): Collection
    {
        $searchUserByKeyword = $this->searchUserByKeyword($keyword);

        $result = $this->user;

        if ($keyword !== "") {
            $result = $result->whereRaw("(username LIKE ? OR
                email LIKE ?)", $searchUserByKeyword)
                ->whereHas("contact", function ($query) use ($keyword) {
                    $searchContactByKeyword = $this->searchContactByKeyword($keyword);

                    $query->whereRaw('(full_name LIKE ? OR
                        nick_name LIKE ? OR
                        country LIKE ? OR
                        state LIKE ? OR
                        city LIKE ? OR
                        mobile LIKE ?)', $searchContactByKeyword);
                });
        }

        if (count($args) > 0 && array_key_exists("status", $args)) {
            $result = $result->where('status', $args['status']);
        }

        if (count($related) > 0) {
            $result = $result->with($related)
                ->withCount(['follower', 'followed']);
        }

        return $result->orderBy($order, $sort)
            ->get();
    }

    public function allSearchPage(string $keyword = "", int $perPage = 10, int $page = 1, string $order = "id",
                                  string $sort = "asc", array $args = [], array $related = []): Paginator
    {
        $searchUserByKeyword = $this->searchUserByKeyword($keyword);

        $result = $this->user;

        if ($keyword !== "") {
            $result = $result->whereRaw("(username LIKE ? OR
                email LIKE ?)", $searchUserByKeyword)
                ->whereHas("contact", function ($query) use ($keyword) {
                    $searchContactByKeyword = $this->searchContactByKeyword($keyword);

                    $query->whereRaw('(full_name LIKE ? OR
                        nick_name LIKE ? OR
                        country LIKE ? OR
                        state LIKE ? OR
                        city LIKE ? OR
                        mobile LIKE ?)', $searchContactByKeyword);
                });
        }

        if (count($args) > 0 && array_key_exists("status", $args)) {
            $result = $result->where("status", $args['status']);
        }

        if (count($related) > 0) {
            $result = $result->with($related)
                ->withCount(['follower', 'followed']);
        }

        return $result->orderBy($order, $sort)
            ->simplePaginate(perPage: $perPage, page: $page);
    }

    public function findById(int|string $id, array $args = [], array $related = []): BaseEntity|null
    {
        $model = $this->user;

        if (count($args) > 0 && array_key_exists("status", $args)) {
            $model = $model->where("status", $args['status']);
        }

        if (count($related) > 0) {
            $model = $model->with($related)
                ->withCount(['follower', 'followed']);
        }

        return  $model->find($id);
    }

    public function save(CreateUserDataRequest $request): BaseEntity
    {
        $user = new $this->user([
            "username" => $request->username,
            "email" => $request->email,
            "role_id" => $request->role_id
        ]);

        $this->setAuditableInformationFromRequest($user, $request);

        $user->setAttribute('password', bcrypt($request->password));

        $user->save();

        $user->contact()->save(new Contact([
            "full_name" => $request->full_name,
            "nick_name" => $request->nick_name,
            "country" => $request->country ?? null,
            "state" => $request->state ?? null,
            "city" => $request->city ?? null,
            "address" => $request->address ?? null,
            "post_code" => $request->post_code ?? null,
            "mobile" => $request->mobile ?? null,
        ]));

        return $user->fresh();
    }

    public function update(UpdateUserDataRequest $request): BaseEntity|null
    {
        $user = $this->user->find($request->id);

        if (!$user) {
            return null;
        }

        $this->setAuditableInformationFromRequest($user, $request);

        $user->username = $request->username ?? $user->username;
        $user->email = $request->email ?? $user->email;

        if ($request->password ?? null) {
            $user->setAttribute('password', bcrypt($request->password));
        }

        $contact = $user->contact;

        $contact->full_name = $request->full_name ?? $contact->full_name;
        $contact->nick_name = $request->nick_name ?? $contact->nick_name;
        $contact->country = $request->country ?? $contact->country;
        $contact->state = $request->state ?? $contact->state;
        $contact->city = $request->city ?? $contact->city;
        $contact->address = $request->address ?? $contact->address;
        $contact->post_code = $request->post_code ?? $contact->post_code;
        $contact->mobile = $request->mobile ?? $contact->mobile;

        $user->save();

        $user->contact()->save($contact);

        return $user->fresh();
    }

    public function delete(string $id): string
    {
        $user = $this->user->find($id);

        return $user->delete();
    }

    public function followUnFollow(string $followerId, string $followedId): int
    {
        $user = $this->user->find($followerId);

        $userFollowed = $this->user->follower()
            ->orWhere('follows.follower_id', $followerId)
            ->where('follows.followed_id', $followedId)
            ->get();

        if ($userFollowed->count() > 0) {
            $user->follower()->detach($followedId);

            return 0;
        }

        $user->follower()->attach($followedId);

        return 1;
    }


    private function searchUserByKeyword(string $keyword) {
        return [
            'username' => '%' . $keyword . '%',
            'email' => '%' . $keyword . '%'
        ];
    }

    private function searchContactByKeyword(string $keyword) {
        return [
            'full_name' => '%' . $keyword . '%',
            'nick_name' => '%' . $keyword . '%',
            'country' => '%' . $keyword . '%',
            'state' => '%' . $keyword . '%',
            'city' => '%' . $keyword . '%',
            'mobile' => '%' . $keyword . '%'
        ];
    }
}
