<?php
namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Room;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::all();
        return view('equipment.index', compact('equipments'));
    }

    public function create()
    {
        $rooms = Room::all();
        return view('equipment.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:equipments',
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        Equipment::create($request->all());

        return redirect()->route('equipment.index')->with('success', 'Équipement ajouté avec succès');
    }

    public function edit(Equipment $equipment)
    {
        $rooms = Room::all();
        return view('equipment.edit', compact('equipment', 'rooms'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $request->validate([
            'name' => 'required|string|unique:equipments,name,' . $equipment->id,
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        $equipment->update($request->all());

        return redirect()->route('equipment.index')->with('success', 'Équipement mis à jour avec succès');
    }
}
