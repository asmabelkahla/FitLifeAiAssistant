@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Détails de l'équipement</h1>
    <div class="mb-3">
        <strong>Nom :</strong> {{ $equipment->name }}
    </div>
    <a href="{{ route('equipment.index') }}" class="btn btn-primary">Retour</a>
</div>
@endsection
