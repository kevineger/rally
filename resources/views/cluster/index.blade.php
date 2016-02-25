@extends('app')

@section('content')
    <h1>rally</h1>
    <hr>
    {!! Form::open(['route' => 'cluster.show','method' => 'GET']) !!}

    <div class="col-lg-6 col-lg-offset-3">
        <div class="input-group">
            <input name="subreddit" type="text" class="form-control" placeholder="Cluster a subreddit">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Search</button>
            </span>
        </div>
    </div>

    {!! Form::close() !!}
@endsection