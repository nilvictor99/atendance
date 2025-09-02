<?php

namespace App\Repositories\Models;

use App\Models\PasswordVault;

class PasswordVaultRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(PasswordVault $model)
    {
        parent::__construct($model, self::RELATIONS);
    }

    public function getModel($search = null, $startDate = null, $endDate = null)
    {
        $query = $this->model->withUserProfile();
        if ($search || $startDate || $endDate) {
            $query->searchData($search, $startDate, $endDate);
        }

        return $query->latest()->paginate(5);
    }
}
