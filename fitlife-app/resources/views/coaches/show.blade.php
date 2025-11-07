@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Détails du coach</h1>
    <div class="mb-3">
        <strong>Nom :</strong> {{ $coach->name }}
    </div>
    <div class="mb-3">
        <strong>Email :</strong> {{ $coach->email }}
    </div>
    <div class="mb-3">
        <strong>Téléphone :</strong> {{ $coach->phone }}
    </div>
    <div class="mb-3">
        <strong>Bio :</strong> {{ $coach->bio }}
    </div>
    <div class="mb-3">
        <strong>Compétences :</strong>
        <ul>
            @forelse($coach->skills as $skill)
                <li>{{ $skill->name }}</li>
            @empty
                <li>Aucune compétence</li>
            @endforelse
        </ul>
    </div>
    <a href="{{ route('coaches.index') }}" class="btn btn-primary">Retour</a>
</div>
@endsection
