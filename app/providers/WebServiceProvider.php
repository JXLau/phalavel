<?php
/**
 * Author: Jason
 */

namespace App\Providers;

use App\Foundation\ServiceProvider;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php;
use Phalcon\Mvc\View\Engine\Volt;

/**
 * Runs only in web environment
 * @package App\Providers
 */
class WebServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    protected function register()
    {
        $this->di->set('view', function () {
            $view = new View();
            $view->setViewsDir(VIEW_PATH . '/');
            $view->registerEngines([
                '.phtml' => Php::class,
                '.html'  => function ($view, $di) {
                    $volt = new Volt($view, $di);

                    $volt->setOptions([
                        'compiledPath'      => STORAGE_PATH . '/framework/views/',
                        'compiledSeparator' => '_',
                        'compileAlways'     => false,
                        'prefix'            => 'phalavel',
                    ]);

                    return $volt;
                },
            ]);

            return $view;
        }, true);
    }
}
