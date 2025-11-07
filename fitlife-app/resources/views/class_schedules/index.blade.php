@extends('Layout.master')

@section('title')
 Classes_Schedule
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center mb-5 pb-3">
        <div class="col-md-12 heading-section ftco-animate text-center">
    <h1 class="text-center">Class Schedules</h1>
    <a href="{{ route('class_schedules.create') }}" class="btn btn-primary">Add New Class</a>
</div>
</div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered text-center">
        <thead>
            <tr class="bg-primary text-white">
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
                <th>Sunday</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($classSchedules as $schedule)
                <tr>
                    <td class="{{ $schedule->day == 'Monday' ? 'text-center' : '' }}">
                        @if ($schedule->day == 'Monday')
                            <div class="img rounded-circle mb-2" style="background-image: url('{{ $schedule->image_url }}');"></div>
                            <a href="#"><strong>{{ $schedule->class_name }}</strong> <br> {{ $schedule->start }}-{{ $schedule->end}}</a>
                        @else
                            <i class="ion-ios-close"></i>
                        @endif
                    </td>
                 
                    <td class="{{ $schedule->day == 'wednesday' ? 'text-center' : '' }}">
                        @if ($schedule->day == 'wednesday')
                            <div class="img rounded-circle mb-2" style="background-image: url('{{ $schedule->image_url }}');"></div>
                            <a href="#"><strong>{{ $schedule->class_name }}</strong> <br> {{ $schedule->start }}-{{ $schedule->end}}</a>
                        @else
                            <i class="ion-ios-close"></i>
                        @endif
                    </td>
                    <td class="{{ $schedule->day == 'thursday' ? 'text-center' : '' }}">
                        @if ($schedule->day == 'Thursday')
                            <div class="img rounded-circle mb-2" style="background-image: url('{{ $schedule->image_url }}');"></div>
                            <a href="#"><strong>{{ $schedule->class_name }}</strong> <br>{{ $schedule->start }}-{{ $schedule->end}}</a>
                        @else
                            <i class="ion-ios-close"></i>
                        @endif
                    </td>
                    <td class="{{ $schedule->day == 'Friday' ? 'text-center' : '' }}">
                        @if ($schedule->day == 'Friday')
                            <div class="img rounded-circle mb-2" style="background-image: url('{{ $schedule->image_url }}');"></div>
                            <a href="#"><strong>{{ $schedule->class_name }}</strong> <br>{{ $schedule->start }}-{{ $schedule->end}}</a>
                        @else
                            <i class="ion-ios-close"></i>
                        @endif
                    </td>
                    <td class="{{ $schedule->day == 'Saturday' ? 'text-center' : '' }}">
                        @if ($schedule->day == 'Saturday')
                            <div class="img rounded-circle mb-2" style="background-image: url('{{ $schedule->image_url }}');"></div>
                            <a href="#"><strong>{{ $schedule->class_name }}</strong> <br>{{ $schedule->start }}-{{ $schedule->end}}</a>
                        @else
                            <i class="ion-ios-close"></i>
                        @endif
                    </td>
                    <td class="{{ $schedule->day == 'Sunday' ? 'text-center' : '' }}">
                        @if ($schedule->day == 'Sunday')
                            <div class="img rounded-circle mb-2" style="background-image: url('{{ $schedule->image_url }}');"></div>
                            <a href="#"><strong>{{ $schedule->class_name }}</strong> <br>{{ $schedule->start }}-{{ $schedule->end}}</a>
                        @else
                            <i class="ion-ios-close"></i>
                        @endif
                    </td>
                 
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><a href="#"><i class="ion-ios-arrow-round-back"></i> September</a></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><a href="#">November <i class="ion-ios-arrow-round-forward"></i></a></th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection