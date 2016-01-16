@extends('app')

@section('head')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endsection

@section('content')
    {{--{{ dd($time_usage) }}--}}
    <h1>rally</h1>
    <h3>Big-Data</h3>
    <hr>
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
    <div class="col-lg-12">
        <div id="overtime" style="width:100%;"></div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['line']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();

            data.addColumn('number', 'Hour');
            @foreach($time_usage[0] as $subreddit => $val)
                data.addColumn({!!"'number', "."'$subreddit'" !!});
            @endforeach
            data.addRows([
                @foreach( $time_usage as $key => $hour )

                {!! '['.$key.', '.$hour[0].', '.$hour[1].', '.$hour[2].', '.$hour[3].', '.$hour[4].'],' !!}

                @endforeach
            ]);
            /*[
             [hour, sub1, sub2, sub3],
             [hour, sub1, sub2, sub3],
             [hour, sub1, sub2, sub3],
             ]*/
//            data.addRows([
//                [1, 37.8, 80.8, 41.8],
//                [2, 30.9, 69.5, 32.4],
//                [3, 25.4, 57, 25.7],
//                [4, 11.7, 18.8, 10.5],
//                [5, 11.9, 17.6, 10.4],
//                [6, 8.8, 13.6, 7.7],
//                [7, 7.6, 12.3, 9.6],
//                [8, 12.3, 29.2, 10.6],
//                [9, 16.9, 42.9, 14.8],
//                [10, 12.8, 30.9, 11.6],
//                [11, 5.3, 7.9, 4.7],
//                [12, 6.6, 8.4, 5.2],
//                [13, 4.8, 6.3, 3.6],
//                [14, 4.2, 6.2, 3.4]
//            ]);

            var options = {
                chart: {
                    title: 'Box Office Earnings in First Two Weeks of Opening',
                    subtitle: 'in millions of dollars (USD)'
                },
                width: 900,
                height: 500,
                axes: {
                    x: {
                        0: {side: 'top'}
                    }
                }
            };

            var chart = new google.charts.Line(document.getElementById('overtime'));

            chart.draw(data, options);

            // create trigger to resizeEnd event
            $(window).resize(function () {
                if (this.resizeTO) clearTimeout(this.resizeTO);
                this.resizeTO = setTimeout(function () {
                    $(this).trigger('resizeEnd');
                }, 500);
            });

            // redraw graph when window resize is completed
            $(window).on('resizeEnd', function () {
                drawChart(data);
            });
        }
    </script>
@endsection