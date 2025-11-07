<?php


namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Display a listing of the attendance records.
    public function index()
    {
        $attendances = Attendance::all();
        return view('attendance', compact('attendances'));
    }

    // Show the form for creating a new attendance record.
    public function create()
    {
        return view('attendances.create');
    }

    // Store a newly created attendance record in storage.
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'session_id' => 'required|exists:sessions,id',
            'attended_at' => 'required|date',
        ]);

        Attendance::create($request->all());
        return redirect()->route('attendances.index');
    }

    // Display the specified attendance record.
    public function show(Attendance $attendance)
    {
        return view('attendances.show', compact('attendance'));
    }

    // Show the form for editing the specified attendance record.
    public function edit(Attendance $attendance)
    {
        return view('attendances.edit', compact('attendance'));
    }

    // Update the specified attendance record in storage.
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'session_id' => 'required|exists:sessions,id',
            'attended_at' => 'required|date',
        ]);

        $attendance->update($request->all());
        return redirect()->route('attendances.index');
    }

    // Remove the specified attendance record from storage.
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index');
    }
}
