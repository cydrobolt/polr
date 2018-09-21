<?php

return array(
    'register' => array(
        'title'       => 'Register',
        'question'    => 'Don\'t have an account?',
        'register'    => 'Register',
        'registerbtn' => 'Register',
        'form'        => array(
            'username' => array(
                'label'       => 'Username:',
                'placeholder' => 'Username',
                'help'        => 'The username you will use to login to :app.',
            ),
            'password' => array(
                'label'       => 'Password:',
                'placeholder' => 'Password',
                'help'        => 'The secure password you will use to login to :app.',
            ),
            'email'    => array(
                'label'       => 'Email:',
                'placeholder' => 'Email',
                'help'        => 'The email you will use to verify your account or to recover your account.',
            ),
        ),
    ),
    'login'    => array(
        'question' => 'Already have an account?',
        'login'    => 'Login',
        'title'    => 'Login',
        'loginbtn' => 'Login',
        'form'     => array(
            'username' => 'Username',
            'password' => 'Password',
        ),
    ),
    'forgot'   => array(
        'question'    => 'Forgot your password?',
        'resetpasswd' => 'Reset',
    ),

);
