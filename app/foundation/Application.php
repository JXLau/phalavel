<?php
/**
 * Author: Jason
 */

namespace App\Foundation;

use Carbon\Carbon;
use Fabfuel\Prophiler\DataCollector\Request;
use Fabfuel\Prophiler\Profiler;
use Fabfuel\Prophiler\Toolbar;
use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Url as UrlResolver;

class Application
{
    /**
     * The Lightning framework version.
     *
     * @var string
     */
    const VERSION = '0.1.0';

    /**
     * @var \Phalcon\DiInterface
     */
    protected $di;
    protected $config;

    protected $loader;

    public function __construct(\Phalcon\Loader $loader)
    {
        $this->config = new Config(array_replace_recursive(
            require CONFIG_PATH . '/config.php',
            require ROOT . '/_'
        ));
        $this->loader = $loader;
        /** @noinspection PhpUndefinedFieldInspection */
        date_default_timezone_set($this->config->timezone);
    }

    public function setFactory($factory)
    {
        switch ($factory) {
            case 'web':
                $this->di = new FactoryDefault();
                break;
            case 'cli':
                $this->di = new CLi;
                break;
            default:
                $this->di = new FactoryDefault();
                break;
        }

        $this->setConfig();
        $this->initializeLogger();
    }

    /**
     * @param \Phalcon\DiInterface $di
     */
    public function setConfig($di = null)
    {
        $this->di->setShared('config', $this->config);
    }

    public function di()
    {
        return $this->di;
    }

    public function registerExceptionHandler()
    {
        // Global error handler & logger
        register_shutdown_function(function () {
            if (!is_null($error = error_get_last())) {

                /** @noinspection PhpUndefinedFieldInspection */
                if (!$this->config->debug && $error['type'] >= E_NOTICE) {
                    return;
                }

                $period = '';
                if ($this->config->log->period == 'daily') {
                    $period = Carbon::today()->format('-Y-m-d');
                }

                $logger = new FileAdapter($this->config->log->path . "error$period.log");

                $logger->error(print_r($error, true));
            }
        });

        // Report errors in debug mode only
        /** @noinspection PhpUndefinedFieldInspection */
        if (!$this->config->debug) {
            error_reporting(0);
        }
    }

    /**
     * Register all of the service providers.
     * @param array $providers
     * @return void
     */
    public function registerServiceProviders(array $providers)
    {
        foreach ($providers as $provider) {
            $provider = new $provider($this->di, $this->config);
            /** @noinspection PhpUndefinedMethodInspection */
            $this->di = $provider->inject();
        }
    }

    /**
     * Register dirs.
     * @param    array                    $dirs [description]
     * @return   [type]                         [description]
     */
    public function registerDirs(array $dirs)
    {
        if (!empty($dirs)) {
            $this->loader->registerDirs($dirs);
            $this->loader->register();    
        }
    }

    private function initializeLogger()
    {
        $config = $this->config;
        $this->di->set('log', function () use ($config) {

            $period = '';
            if ($config->log->period == 'daily') {
                $period = Carbon::today()->format('-Y-m-d');
            }

            return new FileAdapter($config->log->path . "phalavel$period.log");
        });
    }

    public function run()
    {
        $application = new \Phalcon\Mvc\Application($this->di());
        echo $application->handle()->getContent();

        if (!$this->config->debug) {
            return;
        }

        $doProfile = class_exists(Profiler::class);
        if ($doProfile) {
            $profiler = new Profiler();
            if (!defined('DONT_PROFILE')) {
                $toolbar = new Toolbar($profiler);
                $toolbar->addDataCollector(new Request());
                echo $toolbar->render();
            }
        }
    }
}
