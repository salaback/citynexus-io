@extends('master.main')

@section('title', 'Meeting Agenda')

@section('main')

    <div class="col-md-10">
        @foreach($elements as $element)
            @include('meetings.agenda.snipits._' . $element['type'])
        @endforeach
    </div>
@endsection