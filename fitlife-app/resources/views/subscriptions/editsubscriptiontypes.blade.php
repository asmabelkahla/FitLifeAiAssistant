


@extends('Layout.master')

@section('title')
editSubscriptionType
@endsection

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                   <h1>Update Subscription Type</h1>
                </div>
      <div class="card-body">
        <form action="{{ route('updatetype',$subscriptionType->id) }}" method="POST">
        @csrf
      
    
        <div class="form-group">
            <label for="name">Subscription Type</label>
            <input type="text" class="form-control" id="name" name="name" value="{{$subscriptionType->name}}" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{$subscriptionType->price}}" required>
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
