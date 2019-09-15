<?php
namespace App\Providers;

use App\Factories\LinkFactory;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Jumbojett\OpenIDConnectClient;

class OpenIDConnectProvider extends ServiceProvider {

    /**
     * Connect to the OpenID Connect server, if the configuration exists. 
     *
     * @return null
     */
    public function register() {
        if (!env('OPENID_CONNECT_URL')) {
            $this->enabled = false;
            return;
        }

        $this->app->singleton('openidconnect', function ($app) {
            $client = new OpenIDConnectClient(
                env('OPENID_CONNECT_URL'),
                env('OPENID_CONNECT_CLIENT_ID'),
                env('OPENID_CONNECT_CLIENT_SECRET')
            );

            $client->redirectURL = LinkFactory::formatLink('login/openid');

            return $client;
        });
    }

}
