<?php

class ApiLinkControllerTest extends TestCase
{
    protected $apiKey = '1d369353d7067ccbf504ce5360bdb5';

    public function testLookup()
    {
        $params = [
            'key' => $this->apiKey,
            'url_ending' => 'gogl',
        ];
        $this->get('/api/v2/action/lookup?' . http_build_query($params))
            ->see('http://google.com');
    }

    public function testLookupNotFound()
    {
        $params = [
            'key' => $this->apiKey,
            'url_ending' => 'i-dont-exist',
        ];
        $response = $this->call('GET', '/api/v2/action/lookup?' . http_build_query($params));
        $expectedStatusCode = env('SETTING_REDIRECT_404') ? 302 : 404;
        $this->assertEquals($expectedStatusCode, $response->status());
    }

    public function testLookupWrongApiKey()
    {
        $params = [
            'key' => 'wrong',
            'url_ending' => 'gogl',
        ];
        $response = $this->call('GET', '/api/v2/action/lookup?' . http_build_query($params));
        $this->assertEquals(401, $response->status());
    }

    public function testShorten()
    {
        $params = [
            'key' => $this->apiKey,
            'url' => 'http://facebook.com',
            'is_secret' => true,
            'custom_ending' => 'fb'
        ];
        $this->get('/api/v2/action/shorten?' . http_build_query($params))
            ->see(env('APP_ADDRESS') . '/fb');
    }

    public function testShortenDuplicated()
    {
        $params = [
            'key' => $this->apiKey,
            'url' => 'http://google.com',
            'is_secret' => true,
            'custom_ending' => 'gogl'
        ];

        $response = $this->call('GET', '/api/v2/action/shorten?' . http_build_query($params));
        $this->assertEquals(400, $response->status());
    }

    public function testShortenWrongApiKey()
    {
        $params = [
            'key' => 'wrong',
            'url' => 'http://facebook.com',
            'is_secret' => true,
            'custom_ending' => 'fb'
        ];
        $response = $this->call('GET', '/api/v2/action/shorten?' . http_build_query($params));
        $this->assertEquals(401, $response->status());
    }
}
