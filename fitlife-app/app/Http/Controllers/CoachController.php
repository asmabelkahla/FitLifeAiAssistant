<?php
namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Skill;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = Coach::all();
        return view('coaches\index', compact('coaches'));
    }
    public function showTrainers()
    {
        $coaches = Coach::all(); // Récupère tous les entraîneurs
        return view('trainer', compact('coaches')); // Passe la variable à la vue
    }
    public function create()
    {
        $skills = Skill::all(); // Récupère toutes les compétences
        return view('coaches.create', compact('skills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:coaches,email',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $coach = Coach::create($request->except('skills'));

        if ($request->has('skills')) {
            $coach->skills()->sync($request->skills);
        }

        return redirect()->route('coaches.index')->with('success', 'Coach created successfully.');
    }

    public function show(Coach $coach)
    {
        $coach->load('skills'); 
        return view('coaches.show', compact('coach'));
    }

    public function edit(Coach $coach)
    {
        $skills = Skill::all(); // Récupère toutes les compétences
        return view('coaches.edit', compact('coach', 'skills'));
    }

    public function update(Request $request, Coach $coach)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:coaches,email,' . $coach->id,
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $coach->update($request->except('skills'));

        if ($request->has('skills')) {
            $coach->skills()->sync($request->skills);
        }

        return redirect()->route('coaches.index')->with('success', 'Coach updated successfully.');
    }

    public function destroy(Coach $coach)
    {
        $coach->delete();
        return redirect()->route('coaches.index')->with('success', 'Coach deleted successfully.');
    }
}
