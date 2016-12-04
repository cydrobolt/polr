<?php

class AuthTest extends TestCase
{
    /**
     * Test Authentication (sign up and sign in)
     *
     * @return void
     */
    public function testLogin()
    {
        $this->visit('/')
            ->type('polrci', 'username')
            ->type('polrci', 'password')
            ->press('Sign In')
            ->see('Dashboard')
            ->see('Settings')
            ->see('Logout');
    }

    public function testWrongLogin()
    {
        $this->visit('/')
            ->type('polrci', 'username')
            ->type('wrong', 'password')
            ->press('Sign In')
            ->see('Invalid password or inactivated account');
    }

    public function testSettingIsProtected()
    {
        $this->call('GET', '/admin');
        $this->assertResponseStatus(302);
        $this->assertRedirectedToRoute('login');
    }
}
