<div class="dropdown-menu pull-right with-arrow panel panel-default ">
    <ul class="list-group">
        @php($count = 0)
        @forelse(\Illuminate\Support\Facades\Auth::user()->unreadNotifications as $notification)
            @if($notification->type == 'App\Notifications\DataProcessed')
                @include('master.notifications.dataProcessed')
            @elseif($notification->type == 'App\Notifications\ReplyToComment')
                @include('master.notifications.replyToComment')
            @elseif($notification->type == 'App\Notifications\AddedToNewOrganization')
                @include('master.notifications.addedToNewOrganization')
            @elseif($notification->type == 'App\Notifications\AssignedNewTask')
                @include('master.notifications.assignedNewTask')
            @endif

            @php
                if($count > 3)
                    break;
                else
                    $count++;
            @endphp
        @empty
            <li class="list-group-item"> <span class="pull-left media-object media-icon"> <i class="fa fa-star"></i> </span>
                <div class="media-body"> </div>
            </li>

            <li class="list-group-item"> <a role="button" tabindex="0" class="media" href="#"> <span class="pull-left media-object media-icon"> <i class="fa fa-star"></i> </span>
                    <div class="media-body"> <span class="block">Hey! You're all caught up, great job!</span> </div>
                </a> </li>
        @endforelse
    </ul>
    <div class="panel-footer"> <a role="button" tabindex="0">Show all notifications <i class="fa fa-angle-right pull-right"></i></a> </div>
</div>