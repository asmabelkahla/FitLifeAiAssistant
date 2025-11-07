@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Modifier le coach</h1>
    <form action="{{ route('coaches.update', $coach) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $coach->name }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $coach->email }}" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $coach->phone }}">
        </div>
        <div class="mb-3">
            <label for="skills" class="form-label">Compétences</label>
            <select multiple class="form-control" id="skills" name="skills[]">
                @foreach($skills as $skill)
                    <option value="{{ $skill->id }}" {{ $coach->skills->contains($skill->id) ? 'selected' : '' }}>
                        {{ $skill->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea class="form-control" id="bio" name="bio">{{ $coach->bio }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
    </form>
</div>
@endsection
