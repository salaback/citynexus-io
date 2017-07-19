@extends('master.main')

@section('title', 'Municipal Innovation Platform')

@section('main')

    <div class="col-md-4">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>My assigned</strong> tasks</h1>
            </div>
            <div class="boxs-body" style="height: 350px; overflow: scroll;">
                @php($tasks = \App\TaskMgr\Model\Task::where('assigned_to', \Illuminate\Support\Facades\Auth::id())->with('taskList', 'taskList.taskable')->orderBy('due_at', "DESC")->get())
                @if($tasks->count() > 0)
                    <div class="list-group">
                        @foreach($tasks as $task)
                            @if($task->taskList->taskable_type == "App\PropertyMgr\Model\Property")
                                <a class="list-group-item" href="{{route('properties.show', [$task->taskList->taskable_id])}}?tasks=true">
                                    @if($task->due_at == null)
                                        <span class="widget-date" title="{{$task->deleted_at}}">
                                            crated: {{$task->created_at->diffForHumans()}}
                                        </span>
                                    @elseif($task->due_at < \Carbon\Carbon::now())
                                        <span class="widget-date late" title="{{$task->deleted_at}}">
                                            Due: {{$task->due_at->diffForHumans()}}
                                        </span>
                                    @elseif($task->due_at < \Carbon\Carbon::now()->addDays(7))
                                        <span class="widget-date close" title="{{$task->deleted_at}}">
                                            Due: {{$task->due_at->diffForHumans()}}
                                        </span>
                                    @else
                                        <span class="widget-date " title="{{$task->deleted_at}}">
                                            Due: {{$task->due_at->diffForHumans()}}
                                        </span>
                                    @endif
                                    <div class="widget-address">
                                        <i class="fa fa-home"></i> {{$task->taskList->taskable->oneLineAddress}}
                                    </div>
                                    <div class="widget-user">
                                        Created by: {{$task->assigner->fullname}}
                                    </div>
                                    <div class="widget-body">
                                        {{str_limit($task->name, 100, '...')}}
                                    </div>
                                </a>
                            @elseif($task->taskList->taskable_type == "App\PropertyMgr\Model\Entity")
                                <a class="list-group-item" href="{{route('entity.show', [$task->taskList->taskable_id])}}?tasks=true">
                                    <span class="widget-date" title="{{$task->deleted_at}}">
                                        Completed: {{$task->deleted_at->diffForHumans()}}
                                    </span>
                                    <div class="widget-address">
                                        <i class="fa fa-home"></i> {{$task->taskList->taskable->oneLineAddress}}
                                    </div>
                                    <div class="widget-user">
                                        Created to: {{$task->assigner->fullname}}
                                    </div>
                                    <div class="widget-body">
                                        {{str_limit($task->name, 100, '...')}}
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>

    <div class="col-md-4">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Recent</strong> comments</h1>
            </div>
            <div class="boxs-body" style="height: 350px; overflow: scroll;">
                @php($comments = \App\PropertyMgr\Model\Comment::with('commentable')->latest()->limit(20)->get())
                @if($comments->count() > 0)
                    <div class="list-group">
                        @foreach($comments as $comment)
                            @if($comment->cn_commentable_type == "App\PropertyMgr\Model\Property")
                                <a class="list-group-item" href="{{route('properties.show', [$comment->cn_commentable_id])}}">
                                       <span class="widget-date" title="{{$comment->created_at}}">
                                        {{$comment->created_at->diffForHumans()}}
                                        </span>
                                    <div class="widget-address">
                                        <i class="fa fa-home"></i> {{$comment->cn_commentable->oneLineAddress}}
                                    </div>
                                    <div class="widget-user">
                                        Posted by: {{$comment->poster->fullname}}
                                    </div>
                                    <div class="widget-body">
                                        {{str_limit($comment->comment, 100, '...')}}
                                    </div>
                                </a>
                            @elseif($comment->cn_commentable_type == "App\PropertyMgr\Model\Entity")
                                <a class="list-group-item" href="{{route('entity.show.show', [$comment->cn_commentable_id])}}">
                                        <span class="widget-date" title="{{$comment->created_at}}">
                                        {{$comment->created_at->diffForHumans()}}
                                        </span>
                                    <div class="widget-address">
                                        <i class="fa fa-user"></i> {{$comment->cn_commentable->name}}
                                    </div>
                                    <div class="widget-user">
                                        Posted by: {{$comment->poster->fullname}}
                                    </div>
                                    <div class="widget-body">
                                        {{str_limit($comment->comment, 100, '...')}}
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>

    <div class="col-md-4">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Recent</strong> Tags</h1>
            </div>
            <div class="boxs-body" style="height: 350px; overflow: scroll;">
                @php($tags = \Illuminate\Support\Facades\DB::table('cn_tagables')->where('created_at', '>', \Carbon\Carbon::now()->subDays(14))->latest()->limit(20)->get())
                @if($tags->count() > 0)
                    <div class="list-group">
                        @foreach($tags as $tag)
                            @if($tag->tagables_type == "App\PropertyMgr\Model\Property")
                                <a class="list-group-item" href="{{route('properties.show', [$tag->tagables_id])}}">
                                       <span class="widget-date" title="{{$tag->created_at}}">
                                           @php($created_at = new \Carbon\Carbon($tag->created_at))
                                            Tagged: {{$created_at->diffForHumans()}}
                                        </span>
                                    <div class="widget-address">
                                        <i class="fa fa-home"></i> {{\App\PropertyMgr\Model\Property::find($tag->tagables_id)->oneLineAddress}}
                                    </div>
                                    @if(isset($tag->created_by))
                                        <div class="widget-user">
                                            Tagged by: {{\App\User::find($tag->created_by)->fullname}}
                                        </div>
                                    @endif
                                    <div class="widget-body">
                                        <div class="label label-default">{{\App\PropertyMgr\Model\Tag::find($tag->tag_id)->tag}}</div>
                                    </div>
                                </a>
                            @elseif($tag->tagables_type == "App\PropertyMgr\Model\Entity")
                                <a class="list-group-item" href="{{route('entity.show', [$tag->cn_tagables_id])}}">
                                         <span class="widget-date" title="{{$tag->created_at}}">
                                        {{--Tagged: {{\Carbon\Carbon::create($tag->created_at)->diffForHumans()}}--}}
                                        </span>
                                    <div class="widget-address">
                                        <i class="fa fa-user"></i> {{\App\PropertyMgr\Model\Entity::find($tag->tagables_id)->name}}
                                    </div>
                                    @if(isset($tag->created_by))
                                        <div class="widget-user">
                                            Tagged by: {{\App\User::find($tag->created_by)->fullname}}
                                        </div>
                                    @endif
                                    <div class="widget-body">
                                        <div class="label label-default">{{\App\PropertyMgr\Model\Tag::find($tag->tag_id)->tag}}</div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>

    <div class="col-md-4">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Recently created</strong> tasks</h1>
            </div>
            <div class="boxs-body" style="height: 350px; overflow: scroll;">
                @php($tasks = \App\TaskMgr\Model\Task::where('created_at', '>', \Carbon\Carbon::now()->subDays(14))->with('taskList', 'taskList.taskable')->latest()->limit(20)->get())
                @if($tasks->count() > 0)
                    <div class="list-group">
                        @foreach($tasks as $task)
                            @if($task->taskList->taskable_type == "App\PropertyMgr\Model\Property")
                                <a class="list-group-item" href="{{route('properties.show', [$task->taskList->taskable_id])}}?tasks=true">
                                    @if($task->due_at != null)
                                        <span class="widget-date" title="{{$task->due_at}}">
                                        Due: {{$task->due_at->diffForHumans()}}
                                        </span>
                                    @endif
                                    <div class="widget-address">
                                        <i class="fa fa-home"></i> {{$task->taskList->taskable->oneLineAddress}}
                                    </div>
                                    <div class="widget-user">
                                        Assigned to: {{$task->assignee->fullname}}
                                    </div>
                                    <div class="widget-body">
                                        {{str_limit($task->name, 100, '...')}}
                                    </div>
                                </a>
                            @elseif($task->taskList->taskable_type == "App\PropertyMgr\Model\Entity")
                                <a class="list-group-item" href="{{route('entity.show', [$task->taskList->taskable_id])}}?tasks=true">
                                    @if($task->due_at != null)
                                        <span class="widget-date" title="{{$task->due_at}}">
                                        Due: {{$task->due_at->diffForHumans()}}
                                        </span>
                                    @endif
                                    <div class="widget-address">
                                        <i class="fa fa-home"></i> {{$task->taskList->taskable->oneLineAddress}}
                                    </div>
                                    <div class="widget-user">
                                        Assigned to: {{$task->assignee->fullname}}
                                    </div>
                                    <div class="widget-body">
                                        {{str_limit($task->name, 100, '...')}}
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>

    <div class="col-md-4">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Upcoming</strong> tasks</h1>
            </div>
            <div class="boxs-body" style="height: 350px; overflow: scroll;">
                @php($tasks = \App\TaskMgr\Model\Task::whereNotNull('due_at')->where('due_at', '>', \Carbon\Carbon::now()->subDays(14))->with('taskList', 'taskList.taskable')->latest()->limit(20)->get())
                @if($tasks->count() > 0)
                    <div class="list-group">
                        @foreach($tasks as $task)
                            @if($task->taskList->taskable_type == "App\PropertyMgr\Model\Property")
                                <a class="list-group-item" href="{{route('properties.show', [$task->taskList->taskable_id])}}?tasks=true">
                                    @if($task->due_at != null)
                                        <span class="widget-date" title="{{$task->due_at}}">
                                        Due: {{$task->due_at->diffForHumans()}}
                                        </span>
                                    @endif
                                    <div class="widget-address">
                                        <i class="fa fa-home"></i> {{$task->taskList->taskable->oneLineAddress}}
                                    </div>
                                    <div class="widget-user">
                                        Assigned to: {{$task->assignee->fullname}}
                                    </div>
                                    <div class="widget-body">
                                        {{str_limit($task->name, 100, '...')}}
                                    </div>
                                </a>
                            @elseif($task->taskList->taskable_type == "App\PropertyMgr\Model\Entity")
                                <a class="list-group-item" href="{{route('entity.show', [$task->taskList->taskable_id])}}?tasks=true">
                                    @if($task->due_at != null)
                                        <span class="widget-date" title="{{$task->due_at}}">
                                        Due: {{$task->due_at->diffForHumans()}}
                                        </span>
                                    @endif
                                    <div class="widget-address">
                                        <i class="fa fa-home"></i> {{$task->taskList->taskable->oneLineAddress}}
                                    </div>
                                    <div class="widget-user">
                                        Assigned to: {{$task->assignee->fullname}}
                                    </div>
                                    <div class="widget-body">
                                        {{str_limit($task->name, 100, '...')}}
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>

    <div class="col-md-4">
        <section class="boxs">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Recently completed</strong> tasks</h1>
            </div>
            <div class="boxs-body" style="height: 350px; overflow: scroll;">
                @php($tasks = \App\TaskMgr\Model\Task::onlyTrashed()->where('deleted_at', '>', \Carbon\Carbon::now()->subDays(14))->with('taskList', 'taskList.taskable')->limit(20)->get())
                @if($tasks->count() > 0)
                    <div class="list-group">
                        @foreach($tasks as $task)
                            @if($task->taskList->taskable_type == "App\PropertyMgr\Model\Property")

                                {{dd($task)}}
                                <a class="list-group-item" href="{{route('properties.show', [$task->taskList->taskable_id])}}?tasks=true">
                                    <span class="widget-date" title="{{$task->deleted_at}}">
                                        Completed: {{$task->deleted_at->diffForHumans()}}
                                    </span>
                                    <div class="widget-address">
                                        <i class="fa fa-home"></i> {{$task->taskList->taskable->oneLineAddress}}
                                    </div>
                                    <div class="widget-user">
                                        Completed by: {{$task->completee->fullname}}
                                    </div>
                                    <div class="widget-body">
                                        {{str_limit($task->name, 100, '...')}}
                                    </div>
                                </a>
                            @elseif($task->taskList->taskable_type == "App\PropertyMgr\Model\Entity")
                                <a class="list-group-item" href="{{route('entity.show', [$task->taskList->taskable_id])}}?tasks=true">
                                    <span class="widget-date" title="{{$task->deleted_at}}">
                                        Completed: {{$task->deleted_at->diffForHumans()}}
                                    </span>
                                    <div class="widget-address">
                                        <i class="fa fa-home"></i> {{$task->taskList->taskable->oneLineAddress}}
                                    </div>
                                    <div class="widget-user">
                                        Assigned to: {{$task->assignee->fullname}}
                                    </div>
                                    <div class="widget-body">
                                        {{str_limit($task->name, 100, '...')}}
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>



@endsection

@push('scripts')

@endpush

@push('style')

<style>
    .widget-address {
        font-weight: 600;
        font-size: 12pt;
    }
    .widget-user {
        color: #a7aeae;
        font-size: 10pt;
    }
    .widget-body {
    }
    .widget-date {
        float: right;
        color: #a7aeae;
        font-size: 10pt;
    }

    .late {
        color: red;
    }

    .close {
        color: #ff5500;
    }
</style>

@endpush