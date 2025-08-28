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
}
