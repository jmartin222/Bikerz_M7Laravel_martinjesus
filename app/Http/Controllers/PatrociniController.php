<?php

namespace App\Http\Controllers;

use App\Models\Patrocini;
use App\Http\Requests\StorePatrociniRequest;
use App\Http\Requests\UpdatePatrociniRequest;

class PatrociniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePatrociniRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePatrociniRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patrocini  $patrocini
     * @return \Illuminate\Http\Response
     */
    public function show(Patrocini $patrocini)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patrocini  $patrocini
     * @return \Illuminate\Http\Response
     */
    public function edit(Patrocini $patrocini)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePatrociniRequest  $request
     * @param  \App\Models\Patrocini  $patrocini
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePatrociniRequest $request, Patrocini $patrocini)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patrocini  $patrocini
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patrocini $patrocini)
    {
        //
    }
}
