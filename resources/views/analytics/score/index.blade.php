@extends('master.main')

@section('title', 'Property Scores')

@section('main')
    <div class="row">
        <div class="col-md-12">
            <div class="boxs">
                <div class="boxs-header">
                    <span class="custom-font">
                        Property Scores
                    </span>
                </div>

                <div class="boxs-body">
                    {{$scores->links()}}
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    Score Name
                                </th>
                                <th>
                                    Updated Date
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scores as $score)
                            <tr id="score-{{$score->id}}">
                                <td>
                                    {{$score->name}}
                                </td>
                                <td>
                                    {{$score->updated_at->diffForHumans()}}
                                </td>
                                <td>
                                    <a href="{{route('score.edit', [$score->id])}}" class="btn btn-primary btn-raised">Edit Score</a>
                                    <a href="{{route('score.show', [$score->id])}}" class="btn btn-primary btn-raised">Score Results</a>
                                    <a href=# onclick="deleteScore({{$score->id}})" class="btn btn-caution btn-raised">Archive Score</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$scores->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    var deleteScore = function(id)
    {
        $.ajax({
            url: '{{route('score.index')}}/' + id,
            type: 'post',
            data: {
                _token: "{{csrf_token()}}",
                _method: 'delete'
            },
            success: function(){
                $('#score-' + id).fadeOut();
            }
        })
    }
</script>
@endpush