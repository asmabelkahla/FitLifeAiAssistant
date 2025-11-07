<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::all();
        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        return view('admin.activities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
        ]);

        Activity::create($request->all());

        return redirect()->route('activities.index');
    }
    // ActivityController.php
public function edit($id)
{
    $activity = Activity::findOrFail($id);
    return view('admin.activities.edit', compact('activity'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'duration' => 'required|integer',
    ]);

    $activity = Activity::findOrFail($id);
    $activity->update($request->all());

    return redirect()->route('activities.index')->with('success', 'Activité mise à jour avec succès.');
}
public function destroy($id)
{
    $activity = Activity::findOrFail($id);
    $activity->delete();

    return redirect()->route('activities.index')->with('success', 'Activité supprimée avec succès.');
}
}
