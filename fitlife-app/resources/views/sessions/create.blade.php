<!-- resources/views/sessions/create.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Ajouter une Séance</h1>

    <form action="{{ route('sessions.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="activity_id" class="form-label">Activité</label>
            <select id="activity_id" name="activity_id" class="form-select" required>
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="room_id" class="form-label">Salle</label>
            <select id="room_id" name="room_id" class="form-select" required>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="coach_id" class="form-label">Coach</label>
            <select id="coach_id" name="coach_id" class="form-select">
                <option value="">Aucun</option>
                @foreach($coaches as $coach)
                    <option value="{{ $coach->id }}">
                        {{ $coach->user ? $coach->user->name : 'Utilisateur non trouvé' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="start_time" class="form-label">Heure de début</label>
            <input type="datetime-local" id="start_time" name="start_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="end_time" class="form-label">Heure de fin</label>
            <input type="datetime-local" id="end_time" name="end_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="max_participants" class="form-label">Nombre maximum de participants</label>
            <input type="number" id="max_participants" name="max_participants" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter la Séance</button>
    </form>
</div>
@endsection
