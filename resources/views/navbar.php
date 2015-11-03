<div class="container-fluid">
    <nav role="navigation" class="navbar navbar-default navbar-fixed-top">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <!-- Output sign in/sign out buttons appropriately -->
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php"><!-- TODO website name here --></a>
        </div>

        <ul id="navbar" class="nav navbar-collapse collapse navbar-nav" id="nbc">
            <li><a href="about.php">About</a></li>
            <li class="visible-xs"><a href="login.php">Sign In</a></li>
            <li class="visible-xs"><a href="admin/index.php">Dashboard</a></li>
        </ul>
        <ul id="navbar" class="nav pull-right navbar-nav hidden-xs">
            <!-- TODO: only show sign up button if enabled -->
            <li><a href="register.php">Sign Up</a></li>
            <li class="divider-vertical"></li>
            <li class="dropdown">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
                <div class="dropdown-menu pull-right login-dropdown-menu" id="dropdown">
                    <h2>Login</h2>
                    <form action="handle-login.php" method="post" accept-charset="UTF-8">
                        <input id="user_username" type="text" name="username" placeholder='Username' size="30" class="form-control login-form-field">
                        <input id="user_password" type="password" name="password" placeholder='Password' size="30" class="form-control login-form-field">
                        <input class="btn btn-success form-control login-form-submit" type="submit" name="login" value="Sign In">
                        <br><br>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
</div>
