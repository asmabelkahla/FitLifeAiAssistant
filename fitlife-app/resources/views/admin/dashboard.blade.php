<!-- resources/views/admin/dashboard.blade.php -->

@extends('Layout.master')

@section('title')
Dashboard Admin
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h1>Admin Dashboard</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Gestion des Membres -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header">Gestion des Membres</div>
                                <div class="card-body text-center">
                                    <a href="{{ route('members.index') }}" class="btn btn-primary">Voir les Membres</a>
                                </div>
                            </div>
                        </div>

                        <!-- Gestion des Coachs -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header">Gestion des Coachs</div>
                                <div class="card-body text-center">
                                    <a href="{{ route('coaches.index') }}" class="btn btn-primary">Voir les Coachs</a>
                                </div>
                            </div>
                        </div>

                        <!-- Gestion des Salles -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header">Gestion des Salles</div>
                                <div class="card-body text-center">
                                    <a href="{{ route('rooms.index') }}" class="btn btn-primary">Voir les Salles</a>
                                </div>
                            </div>
                        </div>

                        <!-- Gestion des Équipements -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header">Gestion des Équipements</div>
                                <div class="card-body text-center">
                                    <a href="{{ route('equipments.index') }}" class="btn btn-primary">Voir les Équipements</a>
                                </div>
                            </div>
                        </div>

                        <!-- Gestion des Séances -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header">Gestion des Séances</div>
                                <div class="card-body text-center">
                                    <a href="{{ route('sessions.index') }}" class="btn btn-primary">Voir les Séances</a>
                                </div>
                            </div>
                        </div>

                        <!-- Gestion des Activités -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header">Gestion des Activités</div>
                                <div class="card-body text-center">
                                    <a href="{{ route('activities.index') }}" class="btn btn-primary">Voir les Activités</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
