@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Équipements</h1>
    <a href="{{ route('equipment.create') }}" class="btn btn-primary mb-4">Ajouter un équipement</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equipments as $equipment)
    <tr>
        <td>{{ $equipment->id }}</td>
        <td>{{ $equipment->name }}</td>
        <td>{{ $equipment->room->name ?? 'N/A' }}</td>
        <td>{{ $equipment->created_at }}</td>
        <td>{{ $equipment->updated_at }}</td>
    </tr>
@endforeach
        </tbody>
    </table>
</div>
@endsection
