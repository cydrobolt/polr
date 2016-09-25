<?php

class ClickHelperTest extends TestCase
{
    /**
     * @param string $url
     * @param string $expectedHost
     *
     * @dataProvider urlProvider
     */
    public function testHostExtraction($url, $expectedHost)
    {
        $this->assertEquals($expectedHost, \App\Helpers\ClickHelper::getHost($url));
    }

    public function urlProvider()
    {
        return [
            'full' => ['https://www.testdomain.com/abc?test=1#55', 'testdomain.com'],
            'localhost' => ['http://localhost', 'localhost'],
            'ip' => ['http://127.0.0.1', '127.0.0.1'],
            'incorrect_one' => ['unproperUrl', null],
        ];
    }
}
