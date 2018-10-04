<?php

    return [

        'register' => [
            'title'         => 'Register',
            'question'      => 'Don\'t have an account?',
            'register'      => 'Register',
            'registerbtn'   => 'Register',
            'form' => [
                'username' => [
                    'label'         => 'Username:',
                    'placeholder'   => 'Username',
                    'help'          => 'The username you will use to login to :app.',
                ],
                'password' => [
                    'label'         => 'Password:',
                    'placeholder'   => 'Password',
                    'help'          => 'The secure password you will use to login to :app.',
                ],
                'email' => [
                    'label'         => 'Email:',
                    'placeholder'   => 'Email',
                    'help'          => 'The email you will use to verify your account or to recover your account.',
                ],
            ],
        ],
        'login' => [
            'question'      => 'Already have an account?',
            'login'         => 'Login',
            'title'         => 'Login',
            'loginbtn'      => 'Login',
            'form' => [
                'username'  => 'Username',
                'password'  => 'Password',
            ],
        ],
        'forgot' => [
            'question'      => 'Forgot your password?',
            'resetpasswd'   => 'Reset',
        ],

    ];
