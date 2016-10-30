<?php

class UserTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        \App\Models\User::create([
            'username' => 'polrci',
            'password' => \Illuminate\Support\Facades\Hash::make('polrci'),
            'email' => 'polr@admin.tld',
            'role' => 'admin',
            'active' => '1'
        ]);
    }
}

