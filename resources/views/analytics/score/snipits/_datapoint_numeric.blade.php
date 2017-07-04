    <h4>Data Point Stats</h4>
    <div class="row">
        <div class="col-xs-4">
            <strong>Min:</strong> <br>
            <span id="datapointMin"></span>
        </div>
        <div class="col-xs-4">
        <strong>Max:</strong> <br>
            <span id="datapointMax"></span>
        </div>
        <div class="col-xs-4">
            <strong>Mean:</strong> <br>
            <span id="datapointMean"></span>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <strong>Count:</strong> <br>
            <span id="datapointCount"></span>
        </div>
        <div class="col-xs-4">
            <strong>Median:</strong> <br>
            <span id="datapointMedian"></span>
        </div>
        <div class="col-xs-4">
            <strong>Standard Div:</strong> <br>
            <span id="datapointStdDiv"></span>
        </div>
    </div>

@push('scripts')
<script>

    var numericDatapoint = function(data)
    {
        $('#numeric_wrapper').removeClass('hidden');
        $('#datapointStdDiv').html(data.stats.stdDiv);
        $('#datapointMin').html(data.stats.min);
        $('#datapointMax').html(data.stats.max);
        $('#datapointCount').html(data.stats.count);
        $('#datapointMean').html(data.stats.mean);
        $('#datapointMedian').html(data.stats.median);
    };

</script>
@endpush