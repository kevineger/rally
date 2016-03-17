@extends('app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    @include('page-header')
    <div class="ui grid">
        <div class="equal height row">
            <div class="ten wide column">
                <div class="segment">
                    <h1 class="ui header">
                        Clustering
                        <div class="sub header">A clustering of post behaviour</div>
                    </h1>
                    <div class="ui active inverted dimmer">
                        <div class="ui medium text loader">Loading</div>
                    </div>
                    <div class="cluster-container"></div>
                </div>
            </div>
            <div class="six wide column">
                <h1 class="ui header">
                    Sub Info
                    <div class="sub header">Information specific to /r/{{ $subreddit }}</div>
                </h1>
                <br>
                <a class="ui card" href="http://www.reddit.com/r/{{ $subreddit }}">
                    <div class="content">
                        <img class="right floated mini ui image"
                             src="{{ $about->header_img == null ? '/photos/alien.png' : $about->header_img}}">
                        <div class="header">/r/{{ $subreddit }}</div>
                        <div class="meta">
                            <span class="category">{{ $about->id }}</span>
                        </div>
                        <div class="description">
                            <div class="ui list">
                                <div class="item">
                                    <div class="header">{{ $about->title }}</div>
                                </div>
                                <div class="item dont-break-out">{{ $about->public_description }}</div>
                                <div class="item">
                                    <i class="comments outline icon"></i>
                                    <div class="content">
                                        Current users: {{ $about->accounts_active }}</div>
                                </div>
                                <div class="item">
                                    <i class="users icon"></i>
                                    <div class="content">
                                        Subscribers: {{ $about->subscribers }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
@endsection

@section('footer')
    <script>
        // On page load, make AJAX request for clustering image (data)
        $(document).ready(function () {
            $('.segment').dimmer('show');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "/test"
            }).success(function (data) {
                $('.cluster-container').append('<p>First AJAX request is complete</p>');
            }).error(function (msg) {
                alert("There was an error doing the first ajax request");
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "/subreddit/get-data",
                data: {
                    subreddit: '{!! $subreddit !!}'
                }
            }).success(function (data) {
                $('.segment').dimmer('hide');
                $('.cluster-container').append('<img class="ui fluid image" src="/' + data.path_to_image + '" alt="Cluster Data">');
            }).error(function (msg) {
                alert("Error: " + msg);
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "/test"
            }).success(function (data) {
                $('.cluster-container').append('<p>Second AJAX request is complete</p>');
            }).error(function (msg) {
                alert("There was an error doing the second ajax request");
            });
        });
    </script>
@endsection