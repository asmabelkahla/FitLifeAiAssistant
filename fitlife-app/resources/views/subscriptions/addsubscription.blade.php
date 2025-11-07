@extends('Layout.master')

@section('title')
AddSubscription
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h1>Create Subscription</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('add') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label >User </label>
                            <input type="text" name="user_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="subscription_type_id">Subscription Type</label>
                            <select name="subscription_type_id" class="form-control" required>
                                @foreach($subscriptionTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->price }} USD)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>
                       
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
