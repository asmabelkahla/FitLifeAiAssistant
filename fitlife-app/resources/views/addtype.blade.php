@extends('Layout.master')

@section('title')
AddType
@endsection


@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h1>Create Subscription Type</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label >Subscription Type</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                       
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" name="price" id="price" class="form-control" required>
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