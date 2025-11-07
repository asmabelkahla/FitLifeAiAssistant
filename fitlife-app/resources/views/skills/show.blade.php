@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Détails de la compétence</h1>
    <div class="mb-3">
        <strong>Nom :</strong> {{ $skill->name }}
    </div>
    <a href="{{ route('skills.index') }}" class="btn btn-primary">Retour</a>
</div>
@endsection
