@extends('app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <h1>rally</h1>
    <hr>
    <h2>Clustering of {{ $subreddit }}</h2>
    <p id="status">Loading</p>
    <div class="cluster-container">
    </div>
@endsection

@section('footer')
    <script>
        // On page load, make AJAX request for clustering image (data)
        $(document).ready(function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "/cluster/get-data",
                data: {
                    subreddit: '{!! $subreddit !!}'
                }
            }).success(function (data) {
                $('#status').hide();
                $('.cluster-container').append('<img src="/' + data.path_to_image + '" alt="Cluster Data">');
            }).error(function (msg) {
                alert("Error: " + msg);
            });
        });
    </script>
@endsection