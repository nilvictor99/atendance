<?php

namespace App\Http\Controllers;

use App\Models\PasswordShare;
use App\Services\Models\PasswordShareService;
use App\Services\Models\UserService;
use Illuminate\Http\Request;

class PasswordShareController extends Controller
{
    private $passwordShareService;

    private $userService;

    public function __construct(PasswordShareService $passwordShareService, UserService $userService)
    {
        $this->passwordShareService = $passwordShareService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $this->passwordShareService->storeData($request->all());

        return redirect()->back()->banner('Contraseña compartida.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PasswordShare $passwordShare)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PasswordShare $passwordShare)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PasswordShare $passwordShare)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PasswordShare $passwordShare)
    {
        //
    }
}
