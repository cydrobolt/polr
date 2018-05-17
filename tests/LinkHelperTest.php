<?php
use App\Helpers\LinkHelper;
use App\Factories\LinkFactory;

class LinkHelperTest extends TestCase
{
    /**
     * Test LinkHelper
     *
     * @return void
     */

    public function testLinkHelperAlreadyShortened() {
        $not_short = [
            'https://google.com',
            'https://example.com/google',
            'https://cydrobolt.com',
            'http://github.com/cydrobolt/polr'
        ];

        $shortened = [
            'https://polr.me/1',
            'http://bit.ly/1PUf6Sw',
            'http://'.env('APP_ADDRESS').'/1',
            'https://goo.gl/2pSp9f'
        ];


        foreach ($not_short as $u) {
            $this->assertEquals(false, LinkHelper::checkIfAlreadyShortened($u));
        }

        foreach ($shortened as $u) {
            $this->assertEquals(true, LinkHelper::checkIfAlreadyShortened($u));
        }
    }

    public function testLinkExists() {
        $link = LinkFactory::createLink('http://example.com/ci', true, null, '127.0.0.1', false, true);
        // assert that existent link ending returns true
        $this->assertNotEquals(LinkHelper::linkExists($link->short_url), false);
        // assert that nonexistent link ending returns false
        $this->assertEquals(LinkHelper::linkExists('nonexistent'), false);
    }

    public function testAddingUtmToLink() {
        $source_link = "https://www.example.com/?utm_source=original";

        $this->assertEquals(LinkHelper::applyUtmToLink($source_link, ['utm_source' => 'changed', 'utm_campaign' => 'new']), "https://www.example.com/?utm_source=changed&utm_campaign=new");
        $this->assertEquals(LinkHelper::applyUtmToLink($source_link, ['utm_campaign' => 'new']), "https://www.example.com/?utm_source=original&utm_campaign=new");
    }
}
