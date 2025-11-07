@extends('Layout.master')

@section('title')
Coaches
@endsection



<!-- Icon Font Stylesheet -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

@section('content')

<section class="ftco-section">
   
    @if(session('success'))
  
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
         
   
   @endif

    <div class="container">
     
        <div class="row justify-content-center mb-5 pb-3">
      <div class="col-md-12 heading-section ftco-animate text-center">
        <h2 class="mb-1">Subscriptions</h2>
        <a href="addsubscriptions" class="btn btn-primary">Add New Subscription </a>
        <!--li class="list-inline-item">
            <a class="btn btn-primary btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Add" href="addsubscriptions">
                <i class="fa fa-plus"></i>
            </a>
        </li-->
        
      </div>
    </div>
            <div class="col-md-12 heading-section ftco-animate text-center">
                <h2 class="mb-1">Coaches</h2>
                <a class="btn btn-primary btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Add" href="{{ route('coaches.create') }}">
                    <i class="fa fa-plus"></i> Add Coach
                </a>
            </div>
        </div>
        <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
    <table class="table table-bordered text-center">
            <thead>
              <tr class="bg-primary text-white">
                <th>Name</th>
                <th>Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>status</th>
                <th>Actions</th>
              </tr>
            </thead>
        <tbody>
            @foreach($subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription->user->name }}</td>
                    <td>{{ $subscription->subscriptionType->name }}</td>
                    <td>{{ $subscription->start_date }}</td>
                    <td>{{ $subscription->end_date }}</td>
                    <td>{{ $subscription->status}} </td>
               
                   
                 
  <td>
    <li class="list-inline-item">
        <form action="{{ route('destroy', $subscription->id) }}" method="POST" style="display:inline-block;">
            @csrf
            <button class="btn btn-danger btn-sm rounded-0" type="submit" data-toggle="tooltip" data-placement="top" title="Delete" style="background-color: gray; color: white; border: none;">
                <i class="fa fa-trash fa-lg"></i>
            </button>
        </form>
    </li>

    <li class="list-inline-item">
        <a class="btn btn-primary btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('editsubscriptions', $subscription->id) }}" style="background-color: blue; color: white; border: none;">
            <i class="fa fa-edit fa-lg"></i>
        </a>
    </li>

    <li class="list-inline-item">
        @if($subscription->status == 'active')
        <form action="{{ route('cancel', $subscription->id) }}" method="POST">
            @csrf
            <button class="btn btn-warning btn-sm rounded-0" type="submit" data-toggle="tooltip" data-placement="top" title="Cancel" style="background-color: orange; color: white; border: none;">
                <i class="fa fa-ban fa-lg"></i>
            </button>
        </form>
        @endif
    </li>
</td>

                     
                      
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
</div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Bio</th>
                                <th>Skills</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coaches as $coach)
                                <tr>
                                    <td>{{ $coach->id }}</td>
                                    <td>{{ $coach->name }}</td>
                                    <td>{{ $coach->email }}</td>
                                    <td>{{ $coach->phone }}</td>
                                    <td>{{ $coach->bio }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @forelse($coach->skills as $skill)
                                                <li>{{ $skill->name }}</li>
                                            @empty
                                                <li>No Skills</li>
                                            @endforelse
                                        </ul>
                                    </td>
                                    <td>
                                        <form action="{{ route('coaches.destroy', $coach) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-0" data-toggle="tooltip" data-placement="top" title="Delete" style="background-color: gray; color: white; border: none;">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                        <a class="btn btn-primary btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('coaches.edit', $coach) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
