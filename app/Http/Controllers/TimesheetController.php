<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use App\Services\Models\TimesheetService;
use App\Services\Models\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TimesheetController extends Controller
{
    private $userService;

    private $timesheetService;

    public function __construct(UserService $userService, TimesheetService $timesheetService)
    {
        $this->userService = $userService;
        $this->timesheetService = $timesheetService;
    }

    public function index()
    {
        return Inertia::render('TimeSheet');
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $staffId = $request->input('staff_id');
        $staff = $this->userService->getStaffsWithTimeSheets();
        $timesheets = $this->timesheetService->getModel($search, $startDate, $endDate, $staffId);

        return Inertia::render('TimeSheet/List', [
            'timesheets' => $timesheets,
            'search' => $search,
            'dateRange' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'staff' => $staff,
            'staffId' => $staffId,
        ]);
    }

    public function generate(Request $request)
    {
        $data = $this->userService->getAuthUser();
        $result = $this->timesheetService->generateQrCode($data);

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Timesheet $timesheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $data = $this->timesheetService->getDataById($request->id);

        return Inertia::render('TimeSheet/Edit', [
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->timesheetService->updateData($request->id, $request->all());

        return redirect()->route('timesheets.list')->banner('Asistencia Actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timesheet $timesheet)
    {
        //
    }

    public function scan(Request $request)
    {
        dd($request->all());
        $this->timesheetService->storeData($request->all());

        return redirect()->route('timesheets.list')->banner('Asistencia Actualizada');
    }
}
