<?php

namespace App\Repositories\Models;

use App\Models\Role;
use App\Models\Timesheet;

class TimesheetRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(Timesheet $model)
    {
        parent::__construct($model, self::RELATIONS);
    }
}
