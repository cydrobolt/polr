<?php

class IndexTest extends TestCase
{
    /**
     * Test Index
     *
     * @return void
     */
    public function testIndex() {
        $this->visit('/')
             ->see('<h1 class=\'title\'>'. env('APP_NAME') .'</h1>') // Ensure page loads correctly
             ->see('<meta name="csrf-token"') // Ensure CSRF protection is enabled
             ->see('>Sign In</a>') // Ensure log in buttons are shown when user is logged out
             ->dontSee('SQLSTATE'); // Ensure database connection is correct
    }
    
    public function testRequestGetNotExistShortUrl() {
    	$response = $this->call('GET', '/notexist');
    	$this->assertTrue($response->isRedirection());
    	$this->assertRedirectedTo(env('SETTING_INDEX_REDIRECT'));
    }
}
	