<?php

namespace App\Http\Controllers;

use App\Models\Ships;
use App\Http\Requests\StoreShipsRequest;
use App\Http\Requests\UpdateShipsRequest;

class ShipsController extends Controller
{
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
    public function store(StoreShipsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ships $ships)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ships $ships)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShipsRequest $request, Ships $ships)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ships $ships)
    {
        //
    }
}
