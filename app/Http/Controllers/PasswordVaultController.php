<?php

namespace App\Http\Controllers;

use App\Models\PasswordVault;
use App\Services\Models\PasswordVaultService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PasswordVaultController extends Controller
{
    private $passwordVaultService;

    public function __construct(PasswordVaultService $passwordVaultService)
    {
        $this->passwordVaultService = $passwordVaultService;
    }

    public function index()
    {
        return Inertia::render('PasswordVault');
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $passwordVaults = $this->passwordVaultService->getModel($search, $startDate, $endDate);

        return Inertia::render('PasswordVault/List', [
            'passwordVaults' => $passwordVaults,
            'search' => $search,
            'dateRange' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('PasswordVault/Create');
    }

    public function generate()
    {
        $generatedPassword = $this->passwordVaultService->generatePassword();

        return response()->json(['password' => $generatedPassword]);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(PasswordVault $passwordVault)
    {
        //
    }

    public function edit(PasswordVault $passwordVault)
    {
        //
    }

    public function update(Request $request, PasswordVault $passwordVault)
    {
        //
    }

    public function destroy(PasswordVault $passwordVault)
    {
        //
    }
}
