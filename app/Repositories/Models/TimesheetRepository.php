<?php

namespace App\Repositories\Models;

use App\Models\Timesheet;

class TimesheetRepository extends BaseRepository
{
    const RELATIONS = [];

    public function __construct(Timesheet $model)
    {
        parent::__construct($model, self::RELATIONS);
    }

    public function getModel($search = null, $startDate = null, $endDate = null, $staffId = null)
    {
        $query = $this->model->withStaffProfile();
        if ($search || $startDate || $endDate || $staffId) {
            $query->searchData($search, $startDate, $endDate, $staffId);
        }

        return $query->latest()->paginate(5);
    }
}
