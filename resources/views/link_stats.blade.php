@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/datatables.min.css'>
<link rel='stylesheet' href='/css/stats.css'>
<link rel='stylesheet' href='/css/jquery-jvectormap.css'>

@endsection

@section('content')
<div ng-controller="StatsCtrl" class="ng-root">
    <div class="stats-header">
        <h3>Stats</h3>
        <p>
            <b>Short Link: </b>
            <a target="_blank" href="{{ env('APP_PROTOCOL') }}/{{ env('APP_ADDRESS') }}/{{ $link->short_url }}">
                {{ env('APP_ADDRESS') }}/{{ $link->short_url }}
            </a>
        </p>
        <p>
            <b>Long Link: </b>
            <a target="_blank" href="{{ $link->long_url }}">{{ $link->long_url }}</a>
        </p>
    </div>

    <div class="row bottom-padding">
        <div class="col-md-8">
            <h4>Traffic over Time</h4> (total: {{ $link->clicks }})
            <canvas id="dayChart"></canvas>
        </div>
        <div class="col-md-4">
            <h4>Traffic sources</h4>
            <canvas id="refererChart"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h4>Map</h4>
            <div id="mapChart"></div>
        </div>
        <div class="col-md-6">
            <h4>Referers</h4>
            <table class="table table-hover" id="refererTable">
                <thead>
                    <tr>
                        <th>Host</th>
                        <th>Clicks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($referer_stats as $referer)
                        <tr>
                            <td>{{ $referer->label }}</td>
                            <td>{{ $referer->clicks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="col-md-6">

        </div>
    </div>
</div>

@endsection

@section('js')
{{-- Load data --}}
<script>
var dayData = JSON.parse('{!! json_encode($day_stats) !!}');
var refererData = JSON.parse('{!! json_encode($referer_stats) !!}');
var countryData = JSON.parse('{!! json_encode($country_stats) !!}');
</script>

{{-- Include extra JS --}}
<script src='/js/lodash.min.js'></script>
<script src='/js/chart.bundle.min.js'></script>
<script src='/js/datatables.min.js'></script>
<script src='/js/jquery-jvectormap.min.js'></script>
<script src='/js/jquery-jvectormap-world-mill.js'></script>
<script src='/js/StatsCtrl.js'></script>

@endsection
