<?php

use App\Factories\LinkFactory;
use App\Factories\UserFactory;
use App\Helpers\LinkHelper;
use App\Helpers\UserHelper;

/**
 * Tests for the link-management API endpoints
 * (action/list, action/rename, action/update, action/toggle, action/delete).
 */
class ApiLinkManagementTest extends TestCase
{
    private function makeApiUser($username, $role = 'default') {
        return UserFactory::createUser(
            $username, $username . '@example.com', 'password',
            1, '127.0.0.1', 'KEY-' . $username, 1, UserHelper::$USER_ROLES[$role]
        );
    }

    private function makeLink($slug, $creator, $long = null) {
        $long = $long ?: 'http://example.com/' . $slug;
        return LinkFactory::createLink($long, false, $slug, '127.0.0.1', $creator, true, true);
    }

    private function apiCall($method, $action, $params) {
        $response = $this->call($method, '/api/v2/action/' . $action, $params);
        return [$response, json_decode($response->getContent(), true)];
    }

    /* ---------------- auth / ownership ---------------- */

    public function testListRequiresKey() {
        $response = $this->call('GET', '/api/v2/action/list', ['response_type' => 'json']);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testDeleteRequiresKey() {
        $response = $this->call('POST', '/api/v2/action/delete', ['url_ending' => 'x', 'response_type' => 'json']);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testCannotManageOthersLink() {
        $this->makeApiUser('alice');
        $this->makeApiUser('bob');
        $this->makeLink('bobslink', 'bob');

        list($response, $json) = $this->apiCall('POST', 'delete', [
            'key' => 'KEY-alice', 'url_ending' => 'bobslink', 'response_type' => 'json'
        ]);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('ACCESS_DENIED', $json['error_code']);
        // bob's link untouched
        $this->assertNotEquals(false, LinkHelper::linkExists('bobslink'));
    }

    public function testAdminCanManageAnyLink() {
        $this->makeApiUser('boss', 'admin');
        $this->makeApiUser('carol');
        $this->makeLink('carolslink', 'carol');

        list($response, $json) = $this->apiCall('POST', 'delete', [
            'key' => 'KEY-boss', 'url_ending' => 'carolslink', 'response_type' => 'json'
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('carolslink', $json['result']['deleted']);
        $this->assertEquals(false, LinkHelper::linkExists('carolslink'));
    }

    /* ---------------- list ---------------- */

    public function testListReturnsOnlyOwnLinks() {
        $this->makeApiUser('dave');
        $this->makeApiUser('erin');
        $this->makeLink('dave1', 'dave');
        $this->makeLink('dave2', 'dave');
        $this->makeLink('erin1', 'erin');

        list($response, $json) = $this->apiCall('GET', 'list', [
            'key' => 'KEY-dave', 'response_type' => 'json'
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $slugs = array_map(function ($l) { return $l['short_url']; }, $json['result']['links']);
        $this->assertContains('dave1', $slugs);
        $this->assertContains('dave2', $slugs);
        $this->assertNotContains('erin1', $slugs);
    }

    public function testListFilter() {
        $this->makeApiUser('fred');
        $this->makeLink('alpha-one', 'fred');
        $this->makeLink('beta-two', 'fred');

        list($response, $json) = $this->apiCall('GET', 'list', [
            'key' => 'KEY-fred', 'query' => 'alpha', 'response_type' => 'json'
        ]);
        $slugs = array_map(function ($l) { return $l['short_url']; }, $json['result']['links']);
        $this->assertContains('alpha-one', $slugs);
        $this->assertNotContains('beta-two', $slugs);
    }

    /* ---------------- rename ---------------- */

    public function testRenameSuccess() {
        $this->makeApiUser('gina');
        $this->makeLink('oldslug', 'gina');

        list($response, $json) = $this->apiCall('POST', 'rename', [
            'key' => 'KEY-gina', 'url_ending' => 'oldslug', 'new_ending' => 'newslug', 'response_type' => 'json'
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('newslug', $json['result']['new_ending']);
        $this->assertNotEquals(false, LinkHelper::linkExists('newslug'));
        $this->assertEquals(false, LinkHelper::linkExists('oldslug'));
    }

    public function testRenameCollisionRejected() {
        $this->makeApiUser('hugo');
        $this->makeLink('taken', 'hugo');
        $this->makeLink('mine', 'hugo');

        list($response, $json) = $this->apiCall('POST', 'rename', [
            'key' => 'KEY-hugo', 'url_ending' => 'mine', 'new_ending' => 'taken', 'response_type' => 'json'
        ]);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('CREATION_ERROR', $json['error_code']);
        // both still exist
        $this->assertNotEquals(false, LinkHelper::linkExists('mine'));
        $this->assertNotEquals(false, LinkHelper::linkExists('taken'));
    }

    public function testRenameInvalidEndingRejected() {
        $this->makeApiUser('ivan');
        $this->makeLink('ivanslink', 'ivan');

        list($response, $json) = $this->apiCall('POST', 'rename', [
            'key' => 'KEY-ivan', 'url_ending' => 'ivanslink', 'new_ending' => 'bad ending!', 'response_type' => 'json'
        ]);
        $this->assertEquals(400, $response->getStatusCode());
    }

    /* ---------------- update ---------------- */

    public function testUpdateLongUrlSuccess() {
        $this->makeApiUser('jane');
        $this->makeLink('jslug', 'jane', 'http://old.example.com');

        list($response, $json) = $this->apiCall('POST', 'update', [
            'key' => 'KEY-jane', 'url_ending' => 'jslug', 'long_url' => 'http://new.example.com', 'response_type' => 'json'
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('http://new.example.com', $json['result']['long_url']);
        $link = LinkHelper::linkExists('jslug');
        $this->assertEquals('http://new.example.com', $link->long_url);
    }

    public function testUpdateRejectsBadUrl() {
        $this->makeApiUser('karl');
        $this->makeLink('kslug', 'karl');

        list($response, $json) = $this->apiCall('POST', 'update', [
            'key' => 'KEY-karl', 'url_ending' => 'kslug', 'long_url' => 'not-a-url', 'response_type' => 'json'
        ]);
        $this->assertEquals(400, $response->getStatusCode());
    }

    /* ---------------- toggle ---------------- */

    public function testToggleDisablesThenEnables() {
        $this->makeApiUser('lena');
        $this->makeLink('lslug', 'lena');

        list($r1, $j1) = $this->apiCall('POST', 'toggle', [
            'key' => 'KEY-lena', 'url_ending' => 'lslug', 'response_type' => 'json'
        ]);
        $this->assertEquals(200, $r1->getStatusCode());
        $this->assertEquals(true, $j1['result']['is_disabled']);

        list($r2, $j2) = $this->apiCall('POST', 'toggle', [
            'key' => 'KEY-lena', 'url_ending' => 'lslug', 'response_type' => 'json'
        ]);
        $this->assertEquals(false, $j2['result']['is_disabled']);
    }

    /* ---------------- delete ---------------- */

    public function testDeleteSuccess() {
        $this->makeApiUser('mike');
        $this->makeLink('mslug', 'mike');

        list($response, $json) = $this->apiCall('POST', 'delete', [
            'key' => 'KEY-mike', 'url_ending' => 'mslug', 'response_type' => 'json'
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(false, LinkHelper::linkExists('mslug'));
    }

    public function testDeleteNotFound() {
        $this->makeApiUser('nina');
        list($response, $json) = $this->apiCall('POST', 'delete', [
            'key' => 'KEY-nina', 'url_ending' => 'doesnotexist', 'response_type' => 'json'
        ]);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('NOT_FOUND', $json['error_code']);
    }
}
