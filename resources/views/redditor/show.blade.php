@extends('app')

@section('head')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
@endsection

@section('content')
    <h1>rally</h1>
    <hr>
    <h3>{{ $redditor->name }}</h3>
    <div class="col-lg-6">
        <h4>User Data</h4>
        <ul>
            <li>Name: {{ $redditor->name }}</li>
            <li>ID: {{ $redditor->id }}</li>
            <li>Redditor since: {{ $redditor_for }}</li>
            <li>Gold: {{ ($redditor->is_gold) ? 'true' : 'false' }}</li>
            <li>Mod: {{ ($redditor->is_mod) ? 'true' : 'false' }}</li>
        </ul>
    </div>
    <div class="col-lg-6">
        <h4>Subreddits</h4>
        <ul>
            @foreach ( $subreddits as $name => $score )
                <li>{{ $name }} - {{ $score }}</li>
            @endforeach
        </ul>
    </div>
    <div class="col-lg-6">
        <h4>Submission Data</h4>
        <ul>
            <li>Most Upvotes: <a href="{{ $top_up_votes->data->url }}">{{ $top_up_votes->data->ups }}</a></li>
            <li>Most Downvotes: <a href="{{ $top_down_votes->data->url }}">{{ $top_down_votes->data->downs }}</a></li>
            <li>Most Recent: <a href="{{ isset($last_submission->url) ? $last_submission->url : $last_submission->link_url }}">{{ $last_submission->score }}</a></li>
            <li>Number of submissions: {{ count($submissions) }}</li>
            <li>Average Submission Karma: {{ $average_submission_karma }}</li>
        </ul>
    </div>
    <div class="col-lg-6">
        <h4>Comment Data</h4>
        <ul><script type="text/javascript" src="https://www.google.com/jsapi"></script>
            <li>Comment Karma: {{ $redditor->comment_karma }}</li>
            <li>Top Comment: <a
                        href="http://www.reddit.com/r/{{ $top_comment->data->subreddit }}/comments/{{ str_replace("t3_", "", $top_comment->data->link_id) }}/link/{{ $top_comment->data->id }}">{{ $top_comment->data->score }}</a>
            </li>
            <li>Worst Comment: <a
                        href="http://www.reddit.com/r/{{ $worst_comment->data->subreddit }}/comments/{{ str_replace("t3_", "", $worst_comment->data->link_id) }}/link/{{ $worst_comment->data->id }}">{{ $worst_comment->data->score }}</a>
            </li>
            <li>Total Comments: {{ $total_comments }}</li>
            <li>Average Comment Karma: {{ $average_comment_karma }}</li>
        </ul>
    </div>
    <div class="col-lg-6">
        <h4>Karma Data</h4>
    </div>

    <div class="col-lg-12">
        <h4>Activity</h4>

        <div id="activity_chart_div" style="width:100%;"></div>
    </div>
    <div class="col-lg-6">
        <h4>Link Karma vs. Time</h4>
    </div>
    <div class="col-lg-6">
        <h4>Comment Karma vs. Time</h4>
    </div>
    <div class="col-lg-6">
        <h4>Link Types</h4>
    </div>
    <div class="col-lg-6">
        <h4>Word Data</h4>
    </div>

@endsection

@section('footer')
    <script type="text/javascript">
        google.load('visualization', '1', {packages: ['corechart']});
        google.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = new google.visualization.DataTable();
            data.addColumn('timeofday', 'Time of Day');
            data.addColumn('number', 'Submissions');

            var hours = new Array(23);

            data.addRows([
                @foreach( $active_hours as $hour => $num)
                    {!! '
                        [['. $hour .', 0, 0], '. $num .'],
                    ' !!}
                @endforeach
            ]);

            var options = {
                legend: {position: 'none'},
                enableInteractivity: true,
                chartArea: {
                    width: '85%'
                },
                hAxis: {
                    viewWindow: {
                        min: [0, 0, 0],
                        max: [23, 0, 0]
                    },
                    gridlines: {
                        count: -1,
                        units: {
                            hours: {format: ['HH:mm', 'ha']}
                        }
                    },
                }
            };

            var chart = new google.visualization.LineChart(
                    document.getElementById('activity_chart_div'));

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