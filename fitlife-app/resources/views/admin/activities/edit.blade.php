<!-- resources/views/admin/activities/edit.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-success text-white">
                    <h1 class="mb-0 text-center">Modifier l'activité</h1>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('activities.update', $activity->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de l'activité</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $activity->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ $activity->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="duration" class="form-label">Durée (en minutes)</label>
                            <input type="number" class="form-control" id="duration" name="duration" value="{{ $activity->duration }}" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
