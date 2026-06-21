<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('units', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create_unit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_name'  => 'required',
            'short_name' => 'required',
            'status'     => 'required',
        ], [
            'unit_name.required'  => 'Unit Name is required',
            'short_name.required' => 'Unit Name is required',
            'status.required'     => 'Please Select Status',
        ]);

        $unit = Unit::create([
            'user_id'    => Auth::id(),
            'unit_name'  => $request->unit_name,
            'short_name' => $request->short_name,
            'status'     => $request->status
        ]);

        return redirect()->route('units.index')
            ->with('success', 'Units created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        $units = Unit::where('user_id', Auth::id())
            ->where('id', $unit->id)
            ->firstOrFail();

        return view('show_unit', compact('units'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        $units = Unit::where('user_id', Auth::id())
            ->where('id', $unit->id)
            ->firstOrFail();

        return view('edit_unit', compact('units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'unit_name'  => 'required',
            'short_name' => 'required',
            'status'     => 'required',
        ], [
            'unit_name.required'  => 'Unit name is required',
            'short_name.required' => 'Unit code is required',
            'status.required'     => 'Please Select Status',
        ]);

        $unit = Unit::where('user_id', Auth::id())
            ->where('id', $unit->id)
            ->update([
                'unit_name'  => $request->unit_name,
                'short_name' => $request->short_name,
                'status'     => $request->status
            ]);

        return redirect()->route('units.index')
            ->with('success', 'Unit Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $units = Unit::where('user_id', Auth::id())
            ->where('id', $unit->id)
            ->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unit Deleted successfully');
    }
}