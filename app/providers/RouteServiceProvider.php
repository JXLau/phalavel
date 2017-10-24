<?php
/**
 * Author: Jason
 */
namespace App\Providers;

use App\Foundation\ServiceProvider;

/**
 * This will always run
 * @package App\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
	protected function register()
    {
    	$routes = array_merge( $this->mapWebRoutes(), $this->mapApiRoutes());

        $this->di->set('router', function() use ($routes) {
            $router = new \Phalcon\Mvc\Router(FALSE);
            foreach ($routes as $path => $route) {

                if (isset($route['methods'])) {
                    $router->add($path, $route, $route['methods']);
                } else {
                    $router->add($path, $route);
                }
            }

            return $router;
        });
    }

   	/**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        return require( ROUTES_PATH . '/web.php' );
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        return require( ROUTES_PATH . '/api.php' );
    }
}