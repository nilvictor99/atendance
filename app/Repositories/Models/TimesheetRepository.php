<?php

namespace App\Repositories\Models;

use App\Models\Timesheet;
use App\Services\Models\UserService;
use App\Services\Utils\QrGeneratorService;

class TimesheetRepository extends BaseRepository
{
    private $qrGeneratorService;

    private $userService;

    const RELATIONS = [];

    public function __construct(Timesheet $model, QrGeneratorService $qrGeneratorService, UserService $userService)
    {
        parent::__construct($model, self::RELATIONS);
        $this->qrGeneratorService = $qrGeneratorService;
        $this->userService = $userService;
    }

    public function getModel($search = null, $startDate = null, $endDate = null, $staffId = null)
    {
        $query = $this->model->withStaffProfile();
        if ($search || $startDate || $endDate || $staffId) {
            $query->searchData($search, $startDate, $endDate, $staffId);
        }

        return $query->latest()->paginate(5);
    }

    public function generateQrCode($data): array
    {
        $options = [
            'format' => 'png',
            'size' => 300,
            'margin' => 10,
            'errorCorrectionLevel' => 'high',
        ];
        $qrCode = $this->qrGeneratorService->generate($data, $options);

        return ['qrCode' => $qrCode];
    }

    public function getDataById($id)
    {
        return $this->model->withStaffProfile()->findOrFail($id);
    }
}
