<!-- resources/views/rooms/show.blade.php -->
<div>
    <h1>Details de la Salle</h1>
    <p>Nom: {{ $room->name }}</p>
    <p>Capacité: {{ $room->capacity }}</p>
    <p>Status: {{ $room->status }}</p>

    <h2>Équipements:</h2>
    <ul>
        @foreach($equipments as $equipment)
            <li>{{ $equipment->name }} ({{ $equipment->type }})</li>
        @endforeach
    </ul>
</div>
