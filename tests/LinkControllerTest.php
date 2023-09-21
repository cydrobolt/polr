<?php

class LinkControllerTest extends TestCase
{
    /**
     * Test LinkController
     *
     * @return void
     */
    public function testRequestGetNotExistShortUrl() {
        $response = $this->call('GET', '/nonexistent');
        $this->assertTrue($response->isRedirection());
        $this->assertRedirectedTo(env('SETTING_INDEX_REDIRECT'));
    }
}
