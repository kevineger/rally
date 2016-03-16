@extends('app')

@section('head')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
@endsection

@section('content')
    @include('page-header')
    <div class="ui one column centered grid">
        <div class="column">
            <div class="ui segment">
                <div class="ui inverted dimmer">
                    <div class="ui medium text loader">Loading</div>
                </div>
                <div id="activityChart"></div>
            </div>
        </div>
        <div class="ui middle aligned four column centered row">
            <div class="column">
                <select style="text-align:center" class="subreddits-multiple" multiple="multiple">
                    @foreach( $subreddits->getRows() as $row )
                        @foreach( $row->getF() as $field )
                            <option value="{{ $field->getV() }}">{{ $field->getV() }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
            <div class="column">
                <div class="ui basic buttons">
                    <button id="top-btn" class="ui button">Top</button>
                    <button id="clear-btn" class="ui button">Clear</button>
                    <button id="refresh" class="ui button">Redraw</button>
                </div>
            </div>
        </div>
    </div>

    @foreach($results as $key => $result)
        <h2 class="ui header">{{ $key }}</h2>
        <table class="ui sortable celled table">
            <thead>
                <tr>
                    @foreach($result->getSchema()->getFields() as $field)
                        <th>{{ $field->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($result->getRows() as $row)
                    <tr>
                        @foreach($row->getF() as $field)
                            <td>{{ $field->getV() }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
@endsection

@section('footer')
    {{--Make table sortable--}}
    <script type="text/javascript" src="{{ asset('js/all.js') }}"></script>
    <script>
        $('table').tablesort();
        $('thead th.subreddit').data('sortBy', function(th, td, tablesort) {
            return td.toLowerCase();
        });
    </script>
    {{--Google Chart--}}
    {!! Lava::render('LineChart', 'myFancyChart', 'activityChart') !!}
    <script>
        function updateChart() {
            // Loading dimmmer
            $('.segment').dimmer('show');
            // Ajax request to get new chart data
            $.getJSON('big-data/updateChart', {subreddits: $('select').val()}, function (dataTableJson) {
                lava.loadData('myFancyChart', dataTableJson, function (chart) {
                    // Remove loading dimmer
                    $('.segment').dimmer('hide');
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
        $("#top-btn").on("click", function () {
            $multiSelect.val([{!! $default_vals !!}]).trigger("change");
            updateChart();
        });
        $("#clear-btn").on("click", function () {
            $multiSelect.val(null).trigger("change");
        });
    </script>
@endsection