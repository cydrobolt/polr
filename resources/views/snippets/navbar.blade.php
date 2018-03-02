<div class="container-fluid">
    <nav role="navigation" class="navbar navbar-default navbar-fixed-top">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">@lang('snippets.togglenav')</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Output sign in/sign out buttons appropriately -->
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('index') }}">{{env('APP_NAME')}}</a>
        </div>

        <ul id="navbar" class="nav navbar-collapse collapse navbar-nav" id="nbc">
		    <li><a href="{{ route('about') }}">@lang('snippets.about')</a></li>

            @if (empty(session('username')))
                <li class="visible-xs"><a href="{{ route('login') }}">@lang('snippets.signin')</a></li>
                @if (env('POLR_ALLOW_ACCT_CREATION'))
                    <li class="visible-xs"><a href="{{ route('signup') }}">@lang('snippets.signup')</a></li>
                @endif
            @else
                <li class="visible-xs"><a href="{{ route('admin') }}">@lang('snippets.dashboard')</a></li>
                <li class="visible-xs"><a href="{{ route('admin') }}#settings">@lang('snippets.settings')</a></li>
                <li class="visible-xs"><a href="{{ route('logout') }}">@lang('snippets.logout')</a></li>
            @endif
        </ul>

        <ul id="navbar" class="nav pull-right navbar-nav hidden-xs">
            <li class="divider-vertical"></li>

            @if (empty(session('username')))
                @if (env('POLR_ALLOW_ACCT_CREATION'))
                    <li><a href="{{route('signup')}}">@lang('snippets.signup')</a></li>
                @endif

                <li class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">@lang('snippets.signin') <strong class="caret"></strong></a>
                    <div class="dropdown-menu pull-right login-dropdown-menu" id="dropdown">
                        <h2>@lang('snippets.login')</h2>
                        <form action="login" method="POST" accept-charset="UTF-8">
                            <input type="text" name="username" placeholder='@lang('account.login.form.username')' size="30" class="form-control login-form-field" />
                            <input type="password" name="password" placeholder='@lang('account.login.form.password')' size="30" class="form-control login-form-field" />
                            <input type="hidden" name='_token' value='{{csrf_token()}}' />
                            <input class="btn btn-success form-control login-form-submit" type="submit" name="login" value="@lang('snippets.login')" />
                        </form>
                    </div>
                </li>
            @else
                <div class='nav pull-right navbar-nav'>
                    <li class='dropdown'>
                    <a class="dropdown-toggle login-name" href="#" data-toggle="dropdown">{{session('username')}} <strong class="caret"></strong></a>
                        <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu">
                            <li><a tabindex="-1" href="{{ route('admin') }}">@lang('snippets.dashboard')</a></li>
                            <li><a tabindex="-1" href="{{ route('admin') }}#settings">@lang('snippets.settings')</a></li>
                            <li><a tabindex="-1" href="{{ route('logout') }}">@lang('snippets.logout')</a></li>
                        </ul>
                    </li>
                </div>
            @endif
        </ul>
    </nav>
</div>
