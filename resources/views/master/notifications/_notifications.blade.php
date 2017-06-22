<div class="dropdown-menu pull-right with-arrow panel panel-default ">
    <ul class="list-group">
        @forelse(\Illuminate\Support\Facades\Auth::user()->notifications as $notification)
            @if($notification->type == 'App\Notifications\DataProcessed')
                @include('master.notifications.dataProcessed')
            @elseif($notification->type == 'App\Notifications\ReplyToComment')
                @include('master.notifications.replyToComment')
            @elseif($notification->type == 'App\Notifications\AddedToNewOrganization')
                @include('master.notifications.addedToNewOrganization')
            @elseif($notification->type == 'App\Notifications\AssignedNewTask')
                @include('master.notifications.assignedNewTask')
            @endif
        @empty
        @endforelse
    </ul>
    <div class="panel-footer"> <a role="button" tabindex="0">Show all notifications <i class="fa fa-angle-right pull-right"></i></a> </div>
</div>