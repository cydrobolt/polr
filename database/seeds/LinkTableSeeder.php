<?php

class LinkTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        \App\Models\Link::insert([
            [
                'short_url' => 'gogl',
                'long_url' => 'http://google.com',
                'secret_key' => null,
                'is_disabled' => false,
                'ip' => '127.0.0.1',
                'creator' => 'polrci'
            ], [
                'short_url' => 'amz',
                'long_url' => 'http://amazon.com',
                'secret_key' => '42',
                'is_disabled' => false,
                'ip' => '127.0.0.1',
                'creator' => 'polrci'
            ], [
                'short_url' => 'appl',
                'long_url' => 'http://apple.com',
                'secret_key' => null,
                'is_disabled' => true,
                'ip' => '127.0.0.1',
                'creator' => 'polrci'
            ]
        ]);
    }
}

