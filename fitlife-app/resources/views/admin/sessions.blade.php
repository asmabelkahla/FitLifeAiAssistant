@extends('admin.dashboard')

@section('admin-content')
<div class="container">
    <h2>Sessions</h2>
    <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary mb-3">Create New Session</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Coach</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sessions as $session)
                <tr>
                    <td>{{ $session->name }}</td>
                    <td>{{ $session->description }}</td>
                    <td>{{ $session->coach->name }}</td>
                    <td>{{ $session->start_time }}</td>
                    <td>{{ $session->end_time }}</td>
                    <td>
                        <a href="{{ route('admin.sessions.edit', $session->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.sessions.destroy', $session->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
