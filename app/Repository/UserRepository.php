<?php

namespace App\Repository;

use App\Application\Request\Auth\RegisterDataRequest;
use App\Core\Domain\BaseEntity;
use App\Domain\Contact;
use App\Domain\User;
use App\Repository\Contract\IUserRepository;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;

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
            "role_id" => $request->role_id
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
}
