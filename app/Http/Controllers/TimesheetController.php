<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('TimeSheet');
    }

    public function list()
    {
        return Inertia::render('TimeSheet/List');
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
    public function edit(Timesheet $timesheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timesheet $timesheet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timesheet $timesheet)
    {
        //
    }
}
