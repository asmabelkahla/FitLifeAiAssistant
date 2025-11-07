<?php

namespace App\Http\Controllers;
use App\Models\ClassSchedule;

use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    //

    public function index()
    {
        $classSchedules = ClassSchedule::all();
        return view('class_schedules.index', compact('classSchedules'));
    }

    public function create()
    {
        return view('class_schedules.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'day' => 'required|string|max:255',
            'class_name' => 'required|string|max:255',
            'start' => 'required|max:255',
            'end' => 'required|max:255',
            'image_url' => 'required|url',
        ]);

        ClassSchedule::create($validatedData);

        return redirect()->route('classschedule')->with('success', 'Class Schedule created successfully.');
    }
}
