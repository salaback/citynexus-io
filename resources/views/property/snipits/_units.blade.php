<section class="boxs boxs-simple">

    <!-- boxs header -->
    <div class="boxs-header dvd dvd-btm">
        <h1 class="custom-font"><strong>{{$units->count()}} {{str_plural('Unit', $units->count())}}</strong> at this property</h1>
    </div>
    <!-- /boxs header -->

    <!-- boxs body -->
    <div class="boxs-body">
        <div class="list-group unit-list">
            @foreach($units as $unit)
                <a class="list-group-item" href="{{route('properties.show', [$unit->id])}}">{{$unit->unit}}</a>
            @endforeach
        </div>
    </div>
    <!-- /boxs body -->
</section>