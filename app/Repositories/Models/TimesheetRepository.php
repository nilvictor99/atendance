<?php

namespace App\Repositories\Models;

use App\Models\Timesheet;
use App\Services\Models\UserService;
use App\Services\Utils\QrGeneratorService;
use Carbon\Carbon;

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

    public function updateData($id, array $data)
    {
        $timesheet = $this->model->findOrFail($id);
        $updateData = $this->processUpdateData($data);
        $timesheet->update($updateData);

        return $timesheet;
    }

    private function processUpdateData(array $data): array
    {
        $dayIn = $data['date'].' '.$data['day_in'].':00';
        $dayOut = $data['date'].' '.$data['day_out'].':00';

        $dayInCarbon = Carbon::parse($dayIn);
        $dayOutCarbon = Carbon::parse($dayOut);
        $hours = $dayInCarbon->diffInHours($dayOutCarbon) + ($dayInCarbon->diffInMinutes($dayOutCarbon) % 60) / 60;

        return [
            'day_in' => $dayIn,
            'day_out' => $dayOut,
            'hours' => round($hours, 2),
        ];
    }
}
