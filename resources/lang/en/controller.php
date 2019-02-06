<?php

return [

    // admin is for AdminController (just in case ^^)

    'admin' => [
        'please_login' => 'Please login to access your dashboard.',
        'invalid' => 'Invalid or disabled account.',
        'oldpasswdinvalid' => 'Current password invalid. Try again.',
        'passchangesuccess' => 'Password changed successfully.',
    ],

    'admin_pagination' => [
        'delete' => 'Delete',
        'api' => 'API info',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'disable' => 'Disable',
        'enable' => 'Enable',
    ],

    'ajax' => [
        'usernotfound' => 'User not found.',
        'linknotfound' => 'Link not found.',
        'disable' => 'Disable',
        'enable' => 'Enable',
    ],
    
    'controller' => [
        'notadmin' => 'User not admin.',
        'notauth' => 'User must be authenticated',
    ],
    
    'link' => [
        'login' => 'You must be logged in to shorten links.',
        'disabled' => 'Sorry, but this link has been disabled by an administrator.',
    ],

    'setup' => [
        'alreadyran' => 'Sorry, but you have already completed the setup process.',
        'writeerror' => 'Could not write configuration to disk.',
        '403' => 'Transaction unauthorised.',
        'databasefail' => 'Could not create database. Perhaps your credentials were incorrect?',
        'geoipfail' => 'Could not fetch GeoIP database for advanced analytics. Perhaps your server is not connected to the internet?',
        'success' => 'Set up completed! Thanks for using Polr!',
    ],

    'stats' => [
        'invaliddate' => 'Invalid date bounds.',
        'futuredate' => 'Right date bound cannot be in the future.',
        'login' => 'Please login to view link stats.',
        'notfound' => 'Cannot show stats for nonexistent link.',
        'advanalytics' => 'Please enable advanced analytics to view this page.',
        'permission' => 'You do not have permission to view stats for this link.',
        'rightdaterecent' => 'Invalid date bounds.
        The right date bound must be more recent than the left bound.',
    ],

    'user' => [
        'invalidauth' => 'Invalid password or inactivated account. Try again.',
        'registerdisabled' => 'Sorry, but registration is disabled.',
        'wrongcaptcha' => 'You must complete the reCAPTCHA to register.',
        'forbiddenemail' => 'Sorry, your email\'s domain is not permitted to create new accounts.',
        'takenemail' => 'Sorry, your email or username already exists. Try again.',
        'registered' => 'Thanks for signing up! You may now log in.',
        'registeredemail' => 'Thanks for signing up! Please confirm your email to continue.',
        'mailsubject' => ':app Account Activation',
        'nopasswdrecover' => 'Password recovery is disabled.',
        'emailnotexists' => 'Email is not associated with a user.',
        'passwdmailsubject' => ':app Password Reset',
        'recoverpasswd' => 'Password reset email sent. Check your inbox for details.',
        'activated' => 'Account activated. You may now login.',
        'incorrect' => 'Username or activation key incorrect.',
        'passrecover' => 'Password reset. You may now login.',
        'passrecoverfail' => 'Username or reset key incorrect.',
    ],

];