@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Skills</h1>
    <a href="{{ route('skills.create') }}" class="btn btn-primary mb-4">Ajouter une compétence</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($skills as $skill)
                <tr>
                    <td>{{ $skill->id }}</td>
                    <td>{{ $skill->name }}</td>
                    <td>
                        <a href="{{ route('skills.edit', $skill) }}" class="btn btn-primary">Modifier</a>
                        <form action="{{ route('skills.destroy', $skill) }}" method="POST" style="display:inline;">
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
