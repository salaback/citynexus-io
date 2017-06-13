@extends('master.main')

@section('title', 'Meeting Agenda')

@section('main')

    @foreach($elements as $element)
        @include('meetings.agenda.snipits._' . $element['type'])
    @endforeach

@endsection