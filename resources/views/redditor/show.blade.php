@extends('app')

@section('content')
    <h1>rally</h1>
    <hr>
    <h3>{{ $redditor->name }}</h3>
    <div class="col-lg-4">
        <h4>User Data</h4>
        <ul>
            <li>Name: {{ $redditor->name }}</li>
            <li>ID: {{ $redditor->id }}</li>
            <li>Redditor since: {{ $redditor_for }}</li>
            <li>Gold: {{ ($redditor->is_gold) ? 'true' : 'false' }}</li>
            <li>Mod: {{ ($redditor->is_mod) ? 'true' : 'false' }}</li>
        </ul>
    </div>
    <div class="col-lg-4">
        <h4>Subreddits</h4>
        <ul>
            @foreach ( $subreddits as $name => $score )
                <li>{{ $name }} - {{ $score }}</li>
            @endforeach
        </ul>
    </div>
    <div class="col-lg-4">
        <h4>Submission Data</h4>
        <ul>
            <li>Most Upvotes: <a href="{{ $top_up_votes->data->url }}">{{ $top_up_votes->data->ups }}</a></li>
            <li>Most Downvotes: <a href="{{ $top_down_votes->data->url }}">{{ $top_down_votes->data->downs }}</a></li>
            <li>Most Recent: <a href="{{ $last_submission->url }}">{{ $last_submission->score }}</a></li>
            <li>Number of submissions: {{ count($submissions) }}</li>
            <li>Average Karma: {{ $average_karma }}</li>
        </ul>
    </div>
    <div class="col-lg-4">
        <h4>Comment Data</h4>
    </div>
    <div class="col-lg-4">
        <h4>Karma Data</h4>
    </div>
    <div class="col-lg-4">
        <h4>Activity</h4>
    </div>
    <div class="col-lg-4">
        <h4>Link Karma vs. Time</h4>
    </div>
    <div class="col-lg-4">
        <h4>Comment Karma vs. Time</h4>
    </div>
    <div class="col-lg-4">
        <h4>Link Types</h4>
    </div>
    <div class="col-lg-4">
        <h4>Word Data</h4>
    </div>

@endsection