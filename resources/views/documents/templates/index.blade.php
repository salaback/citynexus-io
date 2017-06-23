@extends('master.main')

@section('title', 'Document Queue')

@section('main')

    <div class="col-lg-10">
        <section class="boxs">
            <div class="boxs-header">
                <h1 class="custom-font"><strong>Document </strong>Templates</h1>
                <ul class="controls">
                    <li> <a href="{{route('templates.create')}}" role="button"><i class="fa fa-plus"></i> Create New Template</a></li>
                </ul>
            </div>
            <div class="boxs-body p-0">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Visible From</th>
                        <th>Updated_at</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($templates as $template)
                    <tr>
                        <td>{{$template->name}}</td>
                        <td>
                            @if($template->visible_on != null)
                                @foreach($template->visible_on as $k => $i)
                                    {{title_case($k)}}@unless($loop->last), @endunless
                                @endforeach
                            @endif
                        </td>
                        <td>{{$template->updated_at->diffForHumans()}}</td>
                        <td><a href="{{route('templates.edit', [$template->id])}}" class="btn btn-primary btn-sm btn-raised">Edit</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection