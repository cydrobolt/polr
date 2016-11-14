<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class StatControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testAccessingStatsAsAnonymouse()
    {
        $link = factory(Link::class)->create([
            'short_url' => 'some-nice-shortcut',
            'long_url' => 'https://laravel.com',
        ]);

        $response = $this->call('GET', '/stats/' . $link->id);
        $this->assertTrue($response->isRedirection());
        $this->assertRedirectedTo(route('login'));
    }

    public function testAccessingStatsNotExisingLink()
    {
        $response = $this->call('GET', '/stats/non-existing-link');
        $this->assertTrue($response->isRedirection());
        $this->assertRedirectedTo(route('admin'));
    }

    public function testAccessingStatsAsOwner()
    {
        $creator = factory(User::class)->create();
        $link = factory(Link::class)->create([
            'creator' => $creator->username,
            'short_url' => 'some-nice-shortcut',
            'long_url' => 'https://laravel.com',
        ]);

        $response = $this
            ->withSession(['username' => $creator->username])
            ->call('GET', '/stats/' . $link->id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @param User $user
     *
     * @dataProvider userProvider
     */
    public function testAccessingStatsAsNotOwner(User $user)
    {
        $creator = factory(User::class)->create();
        $link = factory(Link::class)->create([
            'creator' => $creator->username,
            'short_url' => 'some-nice-shortcut',
            'long_url' => 'https://laravel.com',
        ]);

        $response = $this
            ->withSession(['username' => $user->username, 'role' => $user->role])
            ->call('GET', '/stats/' . $link->id);

        if ($user->role === 'admin')
        {
            $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        } else {
            $this->assertRedirectedTo(route('admin'));
        }
    }

    /**
     * @return array
     */
    public function userProvider()
    {
        return [
            'not_admin' => [factory(User::class)->create()],
            'admin' => [factory(User::class)->create(['role' => 'admin'])],
        ];
    }
}