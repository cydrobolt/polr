@extends('layouts.base')

@section('content')
    <h1>Clicks for {{$link->long_url}}</h1>
    <a class="btn btn-info btn-sm pull-right" href="{{route('admin')}}#links" role="button">Go back</a>

    <table class="table table-hover">
        <tr>
            <th>Date</th>
            <th>IP</th>
            <th>Country</th>
            <th>Referer</th>
        </tr>
        @foreach ($clicks as $click)
            <tr>
                <td>{{ $click->date }}</td>
                <td>{{ $click->ip }}</td>
                <td>{{ $click->country }}</td>
                <td><a href="{{ $click->referer }}" title="{{ $click->referer }}" target="_blank">{{ $click->referer_host }}</a></td>

            </tr>
        @endforeach
    </table>
    {!! $clicks->render() !!}
@endsection