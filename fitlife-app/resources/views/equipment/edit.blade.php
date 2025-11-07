@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ isset($equipment) ? 'Modifier' : 'Ajouter' }} un équipement</h1>
    <form action="{{ isset($equipment) ? route('equipment.update', $equipment) : route('equipment.store') }}" method="POST">
        @csrf
        @if(isset($equipment))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ isset($equipment) ? $equipment->name : '' }}" required>
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($equipment) ? 'Mettre à jour' : 'Ajouter' }}</button>
    </form>
</div>
@endsection
