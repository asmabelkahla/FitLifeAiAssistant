@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Sessions</h1>
    <a href="{{ route('sessions.create') }}" class="btn btn-primary mb-4">Ajouter une session</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Activité</th>
                <th>Coach</th>
                <th>Salle</th>
                <th>Heure de début</th>
                <th>Heure de fin</th>
                <th>Max Participants</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessions as $session)
                <tr>
                    <td>{{ $session->id }}</td>
                    <td>{{ $session->activity->name }}</td>
                    <td>{{ $session->coach ? $session->coach->name : 'Aucun' }}</td>
                    <td>{{ $session->room ? $session->room->name : 'Aucune' }}</td>
                    <td>{{ $session->start_time }}</td>
                    <td>{{ $session->end_time }}</td>
                    <td>{{ $session->max_participants }}</td>
                    <td>
                        <a href="{{ route('sessions.edit', $session) }}" class="btn btn-primary">Modifier</a>
                        <form action="{{ route('sessions.destroy', $session) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
