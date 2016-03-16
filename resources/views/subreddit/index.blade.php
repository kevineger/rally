@extends('app')

@section('content')
    @include('page-header')

    <div class="ui grid">
        <div class="six wide column">
            {!! Form::open(['url' => 'subreddit/show','method' => 'GET']) !!}
            <div class="ui fluid large icon input">
                <input name="subreddit" placeholder="Look up a subreddit" type="text">
                <i type="submit" class="inverted circular search link icon"></i>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection