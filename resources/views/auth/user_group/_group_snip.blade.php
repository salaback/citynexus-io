<div class="list-group-item" id="{{$group->id}}_snip">
    {{$group->name}}
    <span class="float-right">
        <i class="fa fa-trash" onclick="removeFromGroup({{$group->id}})"></i>
    </span>
</div>