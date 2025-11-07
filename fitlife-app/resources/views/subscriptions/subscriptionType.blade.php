@extends('Layout.master')

@section('title')
SubscriptionType
@endsection


@section('content')

<div class="container">
    <div class="row justify-content-center mb-5 pb-3">
        <div class="col-md-12 heading-section ftco-animate text-center">
    <h1>Subscription Types</h1>
    <a href="addtypes" class="btn btn-primary">Add New Subscription Type</a>
</div>
</div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subscriptionTypes as $subscriptionType)
                <tr>
                    <td>{{ $subscriptionType->name }}</td>
                    <td>{{ $subscriptionType->price }}dt</td>
                    <td>
                    <li class="list-inline-item">
                        <form action="{{ route('destroytype', $subscriptionType->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button class="btn btn-danger btn-sm rounded-0" type="submit" data-toggle="tooltip" data-placement="top" title="Delete" style="background-color: gray; color: white; border: none;">
                                <i class="fa fa-trash fa-lg"></i>
                            </button>
                        </form>
                    </li>
                
                    <li class="list-inline-item">
                        <a class="btn btn-primary btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('editsubscriptiontype', $subscriptionType->id) }}" style="background-color: blue; color: white; border: none;">
                            <i class="fa fa-edit fa-lg"></i>
                        </a>
                    </li>
                    <td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection