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
        <div id="myStocks"></div>
    </div>
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
@endsection

@section('footer')
    {!! Lava::render('LineChart', 'myFancyChart', 'myStocks') !!}
@endsection