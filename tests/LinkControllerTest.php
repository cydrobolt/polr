<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Link;
use App\Models\User;

class LinkControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testRequestGetNotExistShortUrl() {
        $response = $this->call('GET', '/notexist');
        $this->assertTrue($response->isRedirection());
        $this->assertRedirectedTo(env('SETTING_INDEX_REDIRECT'));
    }

    public function testRequestExitingShortUrl() {
        $mockedHost = 'coming-from-page.com';
        $mockedReferer = 'http://' . $mockedHost .'/subpage';
        $mockedIp = '24.24.24.24';

        $user = factory(User::class)->create();

        $link = factory(Link::class)->create([
            'creator' => $user->username,
            'short_url' => 'some-nice-shortcut',
            'long_url' => 'https://laravel.com',
        ]);

        $response = $this->call(
            'GET',
            '/' . $link->short_url,
            [],
            [],
            [],
            [
                'HTTP_REFERER' => $mockedReferer,
                'REMOTE_ADDR' => $mockedIp
            ]
        );
        $this->assertTrue($response->isRedirection());
        $this->assertRedirectedTo($link->long_url);

        $this->assertEquals(1, count($link->clicks()));

        $click = $link->clicks()->first();

        $this->assertEquals($mockedReferer, $click->referer);
        $this->assertEquals($mockedHost, $click->referer_host);
        $this->assertEquals($mockedIp, $click->ip);
        $this->assertEquals('US', $click->country);
        $this->assertEquals('Symfony/2.X', $click->user_agent);
    }
}
