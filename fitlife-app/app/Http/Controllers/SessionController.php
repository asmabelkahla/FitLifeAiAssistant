<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Activity;
use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\Coach;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = Session::with(['activity', 'coach', 'room'])->get();
        return view('sessions.index', compact('sessions'));
    }

  
    public function create()
    {
        $activities = Activity::all(); // Assurez-vous que le modèle Activity est importé
        $rooms = Room::all(); // Assurez-vous que le modèle Room est importé
        $coaches = Coach::all(); // Récupérez les coachs depuis la table `coaches`
    
        return view('sessions.create', compact('activities', 'rooms', 'coaches'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'room_id' => 'required|exists:rooms,id',
            'coach_id' => 'nullable|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'max_participants' => 'required|integer',
        ]);

        // Créer la séance
        $session = Session::create($validated);

        // Mettre à jour le statut de la salle
        $room = Room::find($validated['room_id']);
        $room->status = 'occupé';
        $room->save();

        return redirect()->route('sessions.index')->with('success', 'Séance ajoutée avec succès');
    }

    public function show(Session $session)
    {
        return view('sessions.show', compact('session'));
    }

    public function edit(Session $session)
    {
        $activities = Activity::all();
        $coaches = User::where('role', 'coach')->get();
        $rooms = Room::all();
        return view('sessions.edit', compact('session', 'activities', 'coaches', 'rooms'));
    }

    public function update(Request $request, Session $session)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'room_id' => 'required|exists:rooms,id',
            'coach_id' => 'nullable|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'max_participants' => 'required|integer',
        ]);

        // Mettre à jour la séance
        $session->update($validated);

        // Mettre à jour le statut de la salle
        $room = Room::find($validated['room_id']);
        $room->status = 'occupé';
        $room->save();

        return redirect()->route('sessions.index')->with('success', 'Séance mise à jour avec succès');
    }

    public function destroy(Session $session)
    {
        // Trouver la salle associée à la séance
        $room = Room::find($session->room_id);
        
        // Supprimer la séance
        $session->delete();

        // Réinitialiser le statut de la salle à 'libre'
        if ($room) {
            $room->status = 'libre';
            $room->save();
        }

        return redirect()->route('sessions.index')->with('success', 'Séance supprimée avec succès');
    }
}
