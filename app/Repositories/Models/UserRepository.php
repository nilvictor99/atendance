<?php

namespace App\Repositories\Models;

use App\Models\User;
use App\Services\Auth\AuthService;

class UserRepository extends BaseRepository
{
    private $AuthService;

    const RELATIONS = [];

    public function __construct(User $model, AuthService $authService)
    {
        parent::__construct($model, self::RELATIONS);
        $this->AuthService = $authService;
    }

    public function getStaffsWithTimeSheets()
    {
        return $this->model->WithStaffs()->get();
    }

    public function getAuthUser()
    {
        $user = $this->AuthService->getAuthUser();
        $user = $this->model->withBasicData()->find($user->id)->setAppends([]);

        return $user;
    }
}
