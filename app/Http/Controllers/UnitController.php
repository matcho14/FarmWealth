<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('units.index', compact('units'));
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name'
        ]);

        Unit::create($request->all());

        return redirect()->route('units.index')->with('success', 'تم إضافة وحدة القياس بنجاح');
    }

    public function show(Unit $unit)
    {
        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name,' . $unit->id
        ]);

        $unit->update($request->all());

        return redirect()->route('units.index')->with('success', 'تم تعديل وحدة القياس بنجاح');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'تم حذف وحدة القياس بنجاح');
    }
}
