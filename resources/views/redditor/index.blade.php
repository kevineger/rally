@extends('app')

@section('content')
    <h1>rally</h1>
    <hr>
    {!! Form::open(['url' => 'redditor/show','method' => 'get']) !!}

    <div class="col-lg-6 col-lg-offset-3">
        <div class="input-group">
            <input name="redditor" type="text" class="form-control" placeholder="Lookup a user by username">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Search</button>
            </span>
        </div>
    </div>

    {!! Form::close() !!}
@endsection