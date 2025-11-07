<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Equipment;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Afficher une liste des salles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $rooms = Room::with('equipments')->get();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Afficher le formulaire de création d'une nouvelle salle.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $equipment = Equipment::all(); // Récupérer tous les équipements disponibles
        return view('rooms.create', compact('equipment'));
    }

    /**
     * Enregistrer une nouvelle salle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:rooms',
            'capacity' => 'required|integer',
            'status' => 'required|string',
            'equipment' => 'array',
            'equipment.*' => 'exists:equipments,id',
        ]);

        $room = Room::create($request->only('name', 'capacity', 'status'));

        // Attacher les équipements sélectionnés à la salle
        $room->equipments()->sync($request->input('equipment', []));

        return redirect()->route('rooms.index')->with('success', 'Salle créée avec succès.');
    }

    /**
     * Afficher une salle spécifique.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\View\View
     */
    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    /**
     * Afficher le formulaire d'édition d'une salle existante.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\View\View
     */
    public function edit(Room $room)
    {
        $equipment = Equipment::all(); // Récupérer tous les équipements disponibles
        return view('rooms.edit', compact('room', 'equipment'));
    }

    /**
     * Mettre à jour une salle existante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|unique:rooms,name,' . $room->id,
            'capacity' => 'required|integer',
            'status' => 'required|string',
            'equipment' => 'array',
            'equipment.*' => 'exists:equipments,id',
        ]);

        $room->update($request->only('name', 'capacity', 'status'));

        // Mettre à jour les équipements associés à la salle
        $room->equipments()->sync($request->input('equipment', []));

        return redirect()->route('rooms.index')->with('success', 'Salle mise à jour avec succès.');
    }

    /**
     * Supprimer une salle.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Salle supprimée avec succès.');
    }
}
