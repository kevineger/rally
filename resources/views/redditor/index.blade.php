@extends('app')

@section('content')
    @include('page-header')

    <div class="ui grid">
        <div class="six wide column">
            {!! Form::open(['url' => 'redditor/show','method' => 'get']) !!}
            <div class="ui fluid large icon input">
                <input name="redditor" placeholder="Lookup a user by username" type="text">
                <i type="submit" class="inverted circular search link icon"></i>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection