@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Ajouter un Équipement</h1>

    <form action="{{ route('equipment.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nom de l'équipement</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="quantity">Quantité</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="room_id">Salle</label>
            <select class="form-control" id="room_id" name="room_id" required>
                <!-- Boucle à travers les salles disponibles -->
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
@endsection
