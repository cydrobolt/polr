<?php

class LinkControllerTest extends TestCase
{
    /**
     * Test LinkController
     *
     * @return void
     */
    public function testRequestGetNotExistShortUrl()
    {
        $response = $this->call('GET', '/notexist');
        $this->assertTrue($response->isRedirection());
        $this->assertRedirectedTo(env('SETTING_INDEX_REDIRECT'));
    }

    public function testProtectedLinks()
    {
        $this->call('GET', '/amz');
        $this->assertResponseStatus(403);
        $this->call('GET', '/amz/wrong');
        $this->assertResponseStatus(403);
        $this->call('GET', '/amz/42');
        $this->assertResponseStatus(302);
        $this->assertRedirectedTo('http://amazon.com');
    }

    public function testUnProtectedLinks()
    {
        $this->call('GET', '/gogl');
        $this->assertResponseStatus(302);
        $this->assertRedirectedTo('http://google.com');
        $this->call('GET', '/gogl/ignored');
        $this->assertResponseStatus(302);
        $this->assertRedirectedTo('http://google.com');
    }

    public function testDisabledLinks()
    {
        $this->call('GET', '/appl');
        $this->assertResponseStatus(302);
        $this->assertRedirectedTo(env('SETTING_INDEX_REDIRECT'));
    }
}
