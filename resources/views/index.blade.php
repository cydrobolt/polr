@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='css/index.css' />
@endsection

@section('content')

<!--h1 class='title'>{{env('APP_NAME')}}</h1-->
<img loading="lazy" width="500" height="57" decoding="async" data-nimg="1" class="glq-home-logo" style="color:transparent" src="https://i.imgur.com/mXIdqe8.png" />
<div class="glq-home-intro">
    <div class="glq-home-title dg"><span>URL Shortener for glq.link</span></div>
    <div class="glq-home-desc">Quickly shorten links for sharing - Integrated into GraphLinq IDE</div>
    <div class="glq-home-connect">

    </div>
</div>
<form method='POST' action='/shorten' role='form'>
    <input type='url' autocomplete='off'
        class='form-control long-link-input' placeholder='https://enter.url/here' name='link-url' />

    <div class='row' id='options' ng-cloak>
        <p>Customize link</p>

        @if (!env('SETTING_PSEUDORANDOM_ENDING'))
        {{-- Show secret toggle only if using counter-based ending --}}
        <div class='btn-group btn-toggle visibility-toggler' data-toggle='buttons'>
            <label class='btn btn-primary btn-sm active'>
                <input type='radio' name='options' value='p' checked /> Public
            </label>
            <label class='btn btn-sm btn-default'>
                <input type='radio' name='options' value='s' /> Secret
            </label>
        </div>
        @endif

        <div>
            <div class='custom-link-text'>
                <h2 class='site-url-field'>{{env('APP_ADDRESS')}}/</h2>
                <input type='text' autocomplete="off" class='form-control custom-url-field' name='custom-ending' />
            </div>
            <div>
                <a href='#' class='btn btn-success btn-xs check-btn' id='check-link-availability'>Check Availability</a>
                <div id='link-availability-status'></div>
            </div>
        </div>
    </div>
    <input type='submit' class='btn btn-info btn-glq' id='shorten' value='Shorten' />
    <a href='#' class='btn btn-success bsecond' id='show-link-options'>Link Options</a>
    <input type="hidden" name='_token' value='{{csrf_token()}}' />
</form>

<div id='tips' class='text-muted tips'>
    <i class='fa fa-spinner'></i> Loading Tips...
</div>
@endsection

@section('js')
<script src='js/index.js'></script>
@endsection
