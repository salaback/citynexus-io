<?php
$pagename = 'City Dashboard';
$section = 'dashboard';
?>

@extends(config('citynexus.template'))

@section(config('citynexus.section'))

    <div class="row">
        <div class="row">

            @foreach($widgets as $widget)
                @include('citynexus::widgets.' . $widget->type)
            @endforeach

        </div><!-- end col -->
    </div>
    <!-- end row -->

@stop

@push('js_footer')

<script src="/vendor/citynexus/plugins/jquery-knob/jquery.knob.js"></script>

<!--Morris Chart-->
<script src="/vendor/citynexus/plugins/morris/morris.min.js"></script>
<script src="/vendor/citynexus/plugins/raphael/raphael-min.js"></script>

<!-- Dashboard init -->
<script src="/vendor/citynexus/pages/jquery.dashboard.js"></script>

<script>
    function removeWidget( id )
    {
        $.ajax({
            url: "{{action('\CityNexus\CityNexus\Http\WidgetController@getRemove')}}/" + id
        }).success(function(){
            $("#widget-" + id).addClass('hidden');

        });
    }

</script>


@endpush

@push('style')

<link rel="stylesheet" href="/vendor/citynexus/plugins/morris/morris.css">

@endpush