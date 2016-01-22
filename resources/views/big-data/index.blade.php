@extends('app')

@section('head')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
@endsection

@section('content')
    {{--{{ dd($time_usage) }}--}}
    <h1>rally</h1>
    <h3>Big-Data</h3>
    <hr>
    <div class="col-lg-12">
        <div id="myStocks"></div>
    </div>
    <div class="col-lg-6 col-lg-offset-3">
        <select class="subreddits-multiple" multiple="multiple" value="funny,pics">
            @foreach( $subreddits->getRows() as $row )
                @foreach( $row->getF() as $field )
                    <option value="{{ $field->getV() }}">{{ $field->getV() }}</option>
                @endforeach
            @endforeach
        </select>
    </div>
    <div class="col-lg-6 col-lg-offset-3">
        <div class="btn-group btn-group-sm">
            <div id="default-btn" class="btn btn-default">
                Top Subreddits
            </div>
            <div id="clear-btn" class="btn btn-default">
                Clear
            </div>
        </div>
        <button id="refresh">Redraw Chart</button>
    </div>

    <div class="col-lg-12">
        @foreach($results as $key => $result)
            <h4>{{ $key }}</h4>
            <table class="table">
                <tr>
                    @foreach($result->getSchema()->getFields() as $field)
                        <th>{{ $field->name }}</th>
                    @endforeach
                </tr>
                @foreach($result->getRows() as $row)
                    <tr>
                        @foreach($row->getF() as $field)
                            <td>{{ $field->getV() }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        @endforeach
    </div>
@endsection

@section('footer')
    {{--Google Chart--}}
    {!! Lava::render('LineChart', 'myFancyChart', 'myStocks') !!}
    <script>
        function updateChart() {
            //TODO: SEND THE CORRECT VALUES
            $.getJSON('big-data/updateChart', {subreddits: $('select').val()}, function (dataTableJson) {
                lava.loadData('myFancyChart', dataTableJson, function (chart) {
                    console.log(chart);
                });
            });
        }
        $("#refresh").click(updateChart);
    </script>
    {{--Select2--}}
    <script type="text/javascript">
        var $multiSelect = $('select').select2();
        $multiSelect.val([{!! $default_vals !!}]).trigger("change");
        // Reload the chart with defaults
        $("#default-btn").click(updateChart);
        $("#clear-btn").on("click", function () {
            $multiSelect.val(null).trigger("change");
        });
    </script>
@endsection