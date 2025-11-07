@extends('Layout.master')

@section('title')
editSubscription
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h1>Update Subscription</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('update',$subscription->id) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label >User </label>
                            <input type="text" name="user_id" value="{{ $subscription->user_id}}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="subscription_type_id">Subscription Type</label>
                            <select name="subscription_type_id" class="form-control" required>
                                @foreach($subscriptionTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->price }} dt)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $subscription->start_date }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $subscription->end_date }}" class="form-control" required>
                        </div>
                       
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
