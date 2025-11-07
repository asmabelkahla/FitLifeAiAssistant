@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Modifier la compétence</h1>
    <form action="{{ route('skills.update', $skill) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $skill->name }}" required>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
    </form>
</div>
@endsection
