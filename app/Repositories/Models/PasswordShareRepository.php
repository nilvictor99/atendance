<?php

namespace App\Repositories\Models;

use App\Models\PasswordShare;
use App\Services\Models\UserService;

class PasswordShareRepository extends BaseRepository
{
    protected $userService;

    const RELATIONS = [];

    public function __construct(PasswordShare $model, UserService $userService)
    {
        parent::__construct($model, self::RELATIONS);
        $this->userService = $userService;
    }

    public function getModel($search = null, $startDate = null, $endDate = null)
    {
        $query = $this->model->withRelations();
        if ($search || $startDate || $endDate) {
            $query->searchData($search, $startDate, $endDate);
        }

        return $query->latest()->paginate(5);
    }

    public function storeData(array $data)
    {
        $records = collect($data['vault_ids'])->map(fn ($vaultId) => [
            'password_id' => $vaultId,
            'shared_with' => $data['user_id'],
            'shared_by' => $this->userService->getAuthUserId(),
            'permissions' => $data['permission'],
        ])->all();

        return $this->createMany($records);
    }

    public function deleteData($id)
    {
        $record = $this->model->findOrFail($id);

        return $record->delete();
    }
}
