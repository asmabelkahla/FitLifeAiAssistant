@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="page-title">Gestion des Coaches</h1>
            <a href="{{ route('coaches.create') }}" class="btn btn-primary">Ajouter un Coach</a>
        </div>
    </div>
    
    <!-- Table Section -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Liste des Coaches</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Bio</th>
                        <th>Compétences</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coaches as $coach)
                        <tr>
                            <td>{{ $coach->id }}</td>
                            <td>{{ $coach->name }}</td>
                            <td>{{ $coach->email }}</td>
                            <td>{{ $coach->phone }}</td>
                            <td>{{ $coach->bio }}</td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @forelse($coach->skills as $skill)
                                        <li>{{ $skill->name }}</li>
                                    @empty
                                        <li>Aucune compétence</li>
                                    @endforelse
                                </ul>
                            </td>
                            <td>
                                <a href="{{ route('coaches.edit', $coach) }}" class="btn btn-warning btn-sm">Modifier</a>
                                <form action="{{ route('coaches.destroy', $coach) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
