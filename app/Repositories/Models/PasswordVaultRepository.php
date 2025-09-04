<?php

namespace App\Repositories\Models;

use App\Models\PasswordVault;
use App\Services\Utils\PasswordGeneratorService;

class PasswordVaultRepository extends BaseRepository
{
    private $PasswordGeneratorService;

    const RELATIONS = [];

    public function __construct(PasswordVault $model, PasswordGeneratorService $PasswordGeneratorService)
    {
        parent::__construct($model, self::RELATIONS);
        $this->PasswordGeneratorService = $PasswordGeneratorService;
    }

    public function getModel($search = null, $startDate = null, $endDate = null)
    {
        $query = $this->model->withUserProfile();
        if ($search || $startDate || $endDate) {
            $query->searchData($search, $startDate, $endDate);
        }

        return $query->latest()->paginate(5);
    }

    public function generatePassword($length = 12)
    {
        return $this->PasswordGeneratorService->generate($length);
    }

    public function storeData(array $data)
    {
        return $this->model->create($data);
    }
}
