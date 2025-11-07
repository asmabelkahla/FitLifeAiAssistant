@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Créer une Séance</h1>
    <form action="{{ route('sessions.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="activity_id">Activité</label>
            <select id="activity_id" name="activity_id" class="form-control" required>
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="room_id">Salle</label>
            <select id="room_id" name="room_id" class="form-control" required>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="coach_id">Coach</label>
            <select id="coach_id" name="coach_id" class="form-control">
                <option value="">Aucun</option>
                @foreach($coaches as $coach)
                    <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="start_time">Heure de début</label>
            <input type="datetime-local" id="start_time" name="start_time" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="end_time">Heure de fin</label>
            <input type="datetime-local" id="end_time" name="end_time" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="max_participants">Nombre maximum de participants</label>
            <input type="number" id="max_participants" name="max_participants" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
@endsection
