@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ isset($room) ? 'Modifier' : 'Ajouter' }} une salle</h1>
    <form action="{{ isset($room) ? route('rooms.update', $room) : route('rooms.store') }}" method="POST">
        @csrf
        @if(isset($room))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ isset($room) ? $room->name : '' }}" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacité</label>
            <input type="number" id="capacity" name="capacity" class="form-control" value="{{ isset($room) ? $room->capacity : '' }}" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">État</label>
            <input type="text" id="status" name="status" class="form-control" value="{{ isset($room) ? $room->status : '' }}" required>
        </div>
        <div class="mb-3">
            <label for="equipment" class="form-label">Équipements</label>
            <select id="equipment" name="equipment[]" class="form-control" multiple>
                @foreach($equipment as $item)
                    <option value="{{ $item->id }}" {{ isset($room) && $room->equipment->contains($item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($room) ? 'Mettre à jour' : 'Ajouter' }}</button>
    </form>
</div>
@endsection
