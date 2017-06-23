@extends('master.main')

@section('title', 'Document Queue')

@section('main')

    <section class="boxs ">
        <div class="boxs-header">
            <h1 class="custom-font"><strong>Document </strong>Print Queue</h1>

        </div>
        <div class="boxs-body p-0">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Queued</th>
                    <th>Template</th>
                    <th>Queued By</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($printJobs as $job)
                <tr>
                    <td>{{$job->created_at->diffForHumans()}}</td>
                    <td>{{$job->template->name}}</td>
                    <td>{{$job->creator->fullname}}</td>
                    <td><div class="btn btn-primary btn-sm btn-raised"></div></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection