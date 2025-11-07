@extends('Layout.master')

@section('title')
 Classes_Schedule
@endsection


@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
            <h1 class="text-center">Add New Class Schedule</h1>
        </div>
        <div class="card-body">
    <form action="{{ route('class_schedules.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="day">Day</label>
            <select class="form-control" id="day" name="day" required>
                <option value="">Select a day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
        </div>
        <div class="form-group">
            <label for="class_name">Class Name</label>
            <input type="text" class="form-control" id="class_name" name="class_name" required>
        </div>
        <div class="form-group">
            <label for="time">Start</label>
            <input type="time" class="form-control" id="time" name="start" required>
        </div>
        <div class="form-group">
            <label for="time">End</label>
            <input type="time" class="form-control" id="time" name="end" required>
        </div>
        <div class="form-group">
            <label for="image_url">Image URL</label>
            <input type="url" class="form-control" id="image_url" name="image_url" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Add Schedule</button>
        </div>
    </form>
</div>
</div>
</div>
</div>
</div>


@endsection