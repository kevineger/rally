@extends('app')

@section('head')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
@endsection

@section('content')
    @include('page-header')
    <div class="ui divided grid">
        <div class="row">
            <div class="ui four wide column">
                <div class="ui fluid card">
                    <div class="image">
                        <img src="{{ '/photos/alien.png' }}">
                    </div>
                    <div class="content">
                        <div class="header">{{ $redditor->name }}</div>
                        <div class="meta">
                            <a>{{ $redditor->id }}</a>
                        </div>
                        <div class="description">
                            Gold: {{ ($redditor->is_gold) ? 'true' : 'false' }}
                            <br>
                            Mod: {{ ($redditor->is_mod) ? 'true' : 'false' }}
                        </div>
                    </div>
                    <div class="extra content">
                        <span class="right floated">
                            Redditor since {{ $redditor_for }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="ui twelve wide column">
                <div id="activity_chart_container">
                    <div style="width: 100%; height: 100%;" id="activity_chart_div"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="eight wide column">
                {{--Submission Data--}}
                <h4 class="ui horizontal divider header">
                    <i class="browser chart icon"></i>
                    Submission Data
                </h4>
                <br>
                <div class="ui three column centered grid">
                    <div class="ui statistic">
                        <div class="value">
                            <i class="newspaper icon"></i> {{ count($submissions) }}
                        </div>
                        <div class="label">
                            Submissions
                        </div>
                    </div>
                    <a href="{{ $top_up_votes->data->url }}">
                        <div class="ui green statistic">
                            <div class="value">
                                {{ $top_up_votes->data->ups }}
                            </div>
                            <div class="label">
                                Top
                            </div>
                        </div>
                    </a>
                    <a href="{{ $top_down_votes->data->url }}">
                        <div class="ui red statistic">
                            <div class="value">
                                {{ $top_down_votes->data->downs }}
                            </div>
                            <div class="label">
                                Worst
                            </div>
                        </div>
                    </a>
                </div>
                <div class="ui two column centered grid">
                    <a href="{{ isset($last_submission->url) ? $last_submission->url : $last_submission->link_url }}">
                        <div class="ui statistic">
                            <div class="value">
                                {{ $last_submission->score }}
                            </div>
                            <div class="label">
                                Most Recent
                            </div>
                        </div>
                    </a>
                    <div class="ui statistic" style="margin-top: 0">
                        <div class="value">
                            {{ round($average_submission_karma, 2) }}
                        </div>
                        <div class="label">
                            Average Karma
                        </div>
                    </div>
                </div>

                <br>
                {{--Comment Data--}}
                <h4 class="ui horizontal divider header">
                    <i class="comment icon"></i>
                    Comment Data
                </h4>
                <br>
                <div class="ui three column centered grid">
                    <div class="ui statistic">
                        <div class="value">
                            <i class="arrow circle up icon"></i> {{ $redditor->comment_karma }}
                        </div>
                        <div class="label">
                            Comment Karma
                        </div>
                    </div>
                    <a href="http://www.reddit.com/r/{{ $top_comment->data->subreddit }}/comments/{{ str_replace("t3_", "", $top_comment->data->link_id) }}/link/{{ $top_comment->data->id }}">
                        <div class="ui green statistic">
                            <div class="value">
                                {{ $top_comment->data->score }}
                            </div>
                            <div class="label">
                                Top
                            </div>
                        </div>
                    </a>
                    <a href="http://www.reddit.com/r/{{ $worst_comment->data->subreddit }}/comments/{{ str_replace("t3_", "", $worst_comment->data->link_id) }}/link/{{ $worst_comment->data->id }}">
                        <div class="ui red statistic">
                            <div class="value">
                                {{ $worst_comment->data->score }}
                            </div>
                            <div class="label">
                                Worst
                            </div>
                        </div>
                    </a>
                </div>
                <div class="ui two column centered grid">
                    <div class="ui statistic">
                        <div class="value">
                            {{ $total_comments }}
                        </div>
                        <div class="label">
                            Total Comments
                        </div>
                    </div>
                    <div class="ui statistic">
                        <div class="value">
                            {{ round($average_comment_karma, 2) }}
                        </div>
                        <div class="label">
                            Average Karma
                        </div>
                    </div>
                </div>
            </div>
            <div class="eight wide column">
                <h4 class="ui horizontal divider header">
                    <i class="tasks icon"></i>
                    Subreddits
                </h4>
                {{--<a class="ui red circular label">2</a>--}}
                <div class="ui three column grid">
                    @foreach (array_chunk($subreddits, 3, true) as $chunk)
                        <div class="row">
                            @foreach ( $chunk as $name => $score )
                                <div class="column">
                                    <p>
                                        @if($score > $average_submission_karma)
                                            <a class="ui teal circular label">{{ $score }}</a>
                                        @else
                                            <a class="ui grey circular label">{{ $score }}</a>
                                        @endif
                                        {{ $name }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
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
//                title: "Activity over Time",
                title: "Activity over Time",
                "titleTextStyle": {
                    fontSize: 20,
                    fontName: "Lato"
                },
                legend: {position: 'none'},
                enableInteractivity: true,
                chartArea: {width: "85%", height: "80%"},
                hAxis: {
                    title: "Time of Day",
                    textPosition: 'out',
                    viewWindow: {
                        min: [0, 0, 0],
                        max: [23, 0, 0]
                    },
                    gridlines: {
                        count: -1,
                        units: {
                            hours: {format: ['HH', 'ha']}
                        }
                    },
                },
                vAxis: {
                    title: "Submissions",
                    textPosition: 'out'
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