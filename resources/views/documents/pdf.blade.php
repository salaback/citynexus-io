@extends('master.pdf')

@section('content')

    @foreach($jobs as $job)

    <div id="template-header-wrapper">
        <img class='template-logo' src="{{config('client.agency_logo')}}" alt="">
        <address>
            @foreach(config('client.address') as $line)
                {{$line}}
                @if($line != null) <br> @endif
            @endforeach
        </address>
    </div>


    {!! $job->document->body !!}
    <div id='footer' class="document-stamp">
        Document: {{$job->document->template->id}}-{{$job->document->id}}<br> Printed: {{\Carbon\Carbon::now()->toDateString()}}
    </div>

    @unless($loop->last) <div class="page-break"></div> @endunless

    @endforeach

@endsection