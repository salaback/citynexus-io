@extends('master.main')

@section('title', 'Search Results')

@section('main')

    <div class="col-md-10">
            <div class="boxs-header dvd dvd-btm">
                <h1 class="custom-font"><strong>Search </strong>Results</h1>
            </div>
            <div class="boxs-body p-0">
                {{$results->appends(['query' => $_GET['query']])->links()}}

                <div class="list-group">
                    @foreach($results as $result)
                        <a href="{{$result->link}}" class="list-group-item">
                            @if($result->type == 'Building')
                                <span class="fa fa-building"></span>
                            @elseif($result->type == 'House')
                                    <span class="fa fa-home"></span>
                            @elseif($result->type == 'Entity')
                                <span class="fa fa-user"></span>
                            @elseif($result->type == 'Tag')
                                <span class="fa fa-tag"></span>
                            @elseif($result->type == 'Data Set')
                                <span class="fa fa-database"></span>
                            @elseif($result->type == 'Comment')
                                <span class="fa fa-comment"></span>
                            @endif
                            {{str_limit($result->search, 200, '...')}}
                        </a>
                       @endforeach
                </div>
                {{$results->appends(['query' => $_GET['query']])->links()}}
            </div>
    </div>

    @endsection