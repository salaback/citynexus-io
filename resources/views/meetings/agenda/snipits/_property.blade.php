<a href="{{route('properties.show', [$property['id']])}}" target="_blank" class="btn btn-primary btn-raised pull-right">Property Detail</a>
<strong>{{$property['address']}}</strong><br>
@if(isset($property['note']))<p>{{$property['note']}}</p>@endif
<div class="row">
    <div class="col-sm-12">
        <div class="label label-default" style="cursor: pointer"><span class="fa fa-comment"></span> Add Comment</div>
        <div class="label label-default" style="cursor: pointer"><span class="fa fa-tag"></span> Add Tag</div>
        <div class="label label-default" style="cursor: pointer"><span class="fa fa-check"></span> Add Task</div>
    </div>
</div>
