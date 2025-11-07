@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Créer une nouvelle activité</div>
                <div class="card-body">
                    <form action="{{ route('activities.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de l'activité</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="duration" class="form-label">Durée (en minutes)</label>
                            <input type="number" class="form-control" id="duration" name="duration" required>
                        </div>
                        <button type="submit" class="btn btn-success">Créer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
