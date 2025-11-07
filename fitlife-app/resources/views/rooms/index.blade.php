@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Salles</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Capacité</th>
                <th>Status</th>
                <th>Équipements</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
                <tr>
                    <td>{{ $room->id }}</td>
                    <td>{{ $room->name }}</td>
                    <td>{{ $room->capacity }}</td>
                    <td>{{ $room->status }}</td>
                    <td>
                        <ul>
                            @foreach($room->equipments as $equipment)
                                <li>{{ $equipment->name }} ({{ $equipment->type }})</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
