<li class="list-group-item"> <a role="button" tabindex="0" class="media" href="{{route('getNotification', [$notification->id])}}"> <span class="pull-left media-object media-icon"> <i class="fa fa-comment"></i> </span>
        <div class="media-body"> <span class="block">{{$notification->data['message']}}</span> <small class="text-muted">{{$notification->created_at->diffForHumans()}}</small> </div>
    </a> </li>