<?php namespace Octoshop\Core;

use Illuminate\Auth\Events\Logout;
use Illuminate\Session\SessionManager;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('cart', 'Octoshop\Core\Cart');

        $this->app['events']->listen(Logout::class, function () {
            if (false) {
                $this->app->make(SessionManager::class)->forget('cart');
            }
        });
    }
}
