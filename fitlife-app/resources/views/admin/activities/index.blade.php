<!-- resources/views/admin/activities/index.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Activités</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Durée</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $activity)
                <tr>
                    <td>{{ $activity->id }}</td>
                    <td>{{ $activity->name }}</td>
                    <td>{{ $activity->description }}</td>
                    <td>{{ $activity->duration }}</td>
                    <td>
                        <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-primary">Modifier</a>
                        <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" style="display:inline;">
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
