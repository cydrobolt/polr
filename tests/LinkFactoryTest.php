<?php


use App\Factories\LinkFactory;

class LinkFactoryTest extends TestCase
{
    public function testLinkIsProhibitedEnding()
    {
        $this->setExpectedException(\Exception::class, 'Sorry, but your ending is a prohibited ending');
        LinkFactory::createLink('https://example.org', true, 'login', '127.0.0.1', false, true);

        $this->setExpectedException(\Exception::class, 'Sorry, but your ending is a prohibited ending');
        LinkFactory::createLink('https://example.org', true, 'js', '127.0.0.1', false, true);
    }
}