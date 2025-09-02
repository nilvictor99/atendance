<?php

namespace App\Services\Models;

use App\Filament\Exports\TimesheetExporter;
use App\Models\User;
use App\Repositories\Models\RoleRepository;
use App\Repositories\Models\TimesheetRepository;
use App\Services\Auth\AuthService;

class TimesheetService extends BaseService
{

    public function __construct(TimesheetRepository $repository)
    {
        parent::__construct($repository);
    }
}
