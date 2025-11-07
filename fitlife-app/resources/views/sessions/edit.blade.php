@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Modifier la séance</h1>
    <form action="{{ route('sessions.update', $session) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="activity_id" class="form-label">Activité</label>
            <select class="form-control" id="activity_id" name="activity_id" required>
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}" @if($activity->id == $session->activity_id) selected @endif>{{ $activity->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="coach_id" class="form-label">Coach</label>
            <select class="form-control" id="coach_id" name="coach_id">
                <option value="">Aucun</option>
                @foreach($coaches as $coach)
                    <option value="{{ $coach->id }}" @if($coach->id == $session->coach_id) selected @endif>{{ $coach->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="room_id" class="form-label">Salle</label>
            <select id="room_id" name="room_id" class="form-control">
                <option value="">Aucune</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ isset($session) && $session->room_id == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="start_time" class="form-label">Date et heure de début</label>
            <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="{{ $session->start_time->format('Y-m-d\TH:i') }}" required>
        </div>
        <div class="mb-3">
            <label for="end_time" class="form-label">Date et heure de fin</label>
            <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="{{ $session->end_time->format('Y-m-d\TH:i') }}" required>
        </div>
        <div class="mb-3">
            <label for="max_participants" class="form-label">Participants max</label>
            <input type="number" class="form-control" id="max_participants" name="max_participants" value="{{ $session->max_participants }}" required>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
    </form>
</div>
@endsection
