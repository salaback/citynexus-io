@extends('master.main')

@section('title', 'Document Queue')

@section('main')

    <div class="col-md-10">
        <section class="boxs ">
            <div class="boxs-header">
                <h1 class="custom-font"><strong>Document </strong>Print Queue</h1>

            </div>
            <div class="boxs-body">
                   @if($printJobs->count() > 0)
                        <form target="_blank" action="{{route('queue.print')}}" method="post" onsubmit="confirmPrint()">
                            {{csrf_field()}}
                            <button class="btn btn-primary btn-sm btn-raised"><i class="fa fa-print"></i> Print Checked</button>

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Queued</th>
                                    <th>Template</th>
                                    <th>Queued By</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($printJobs as $job)
                                    <tr>
                                        <td><input type="checkbox" class="jobs" value="{{$job->id}}" name="jobs[]"></td>
                                        <td>{{$job->created_at->diffForHumans()}}</td>
                                        <td>{{$job->document->template->name}}</td>
                                        <td>{{$job->creator->fullname}}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </form>
                    @else

                        <div class="alert alert-info">
                            Looks like print jobs are all caught up!
                        </div>
                    @endif
                <div class="row"></div>
            </div>
        </section>
    </div>
@endsection

@push('modal')
<div class="modal fade" id="clearQueue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Documents Completed</h4>
            </div>
            <div class="modal-body">
                <p>Did the documents print succesfully?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-raised btn-success" data-dismiss="modal" onclick="removeJobs()">Yes</button>
                <button type="button" class="btn btn-raised btn-danger" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('style')
    <link rel="stylesheet" href="/assets/js/vendor/sweetalert/sweetalert2.css">
@endpush

@push('scripts')
    <script src="/assets/bundles/sweetalertscripts.bundle.js"></script>

    <script>

        var test;

        function confirmPrint() {
            $('#clearQueue').modal('show');
        }
        function removeJobs()
            {
                var documents = $('.jobs');

                var ids = [];

                test = documents;

                for(var i = 0; i < documents.length; i++)
                {
                    ids.push(documents[i].defaultValue);
                }

                $.ajax({
                    url: "{{route('queue.clear')}}",
                    type: "POST",
                    data: {
                        _token: "{{csrf_token()}}",
                        ids: ids,
                    },
                    success: function () {
                        location.reload(true);
                    },
                    error: function() {
                        alert('flash_warning', 'Uh oh. Something went wrong.');
                    }
                })
            }

    </script>

    @endpush