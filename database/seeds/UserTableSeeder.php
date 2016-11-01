<?php

class UserTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        \App\Models\User::create([
            'username' => 'polrci',
            'password' => \Illuminate\Support\Facades\Hash::make('polrci'),
            'email' => 'polr@admin.tld',
            'api_active' => true,
            'api_key' => '1d369353d7067ccbf504ce5360bdb5',
            'api_quota' => '1000',
            'role' => 'admin',
            'active' => '1'
        ]);
    }
}

