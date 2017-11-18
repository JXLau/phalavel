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

class Application
{
    /**
     * Phalavel version
     */
    const VERSION = '0.1.1';

    /**
     * @var \Phalcon\DiInterface
     */
    protected $di;
    /**
     * @var \Phalcon\Config
     */
    protected $config;
    /**
     * @var \Phalcon\Loader
     */
    protected $loader;

    protected $factory;

    public function __construct(\Phalcon\Loader $loader)
    {
        $config_files = glob(CONFIG_PATH . DIRECTORY_SEPARATOR . '*.php');
        $config = [];
        foreach ($config_files as $config_file) {
            $each_config = require $config_file;
            $config = array_merge($each_config, $config);
        }

        $this->config = new Config(array_replace_recursive(
            $config,
            require ROOT . DIRECTORY_SEPARATOR . '_'
        ));

        $this->loader = $loader;
        /** @noinspection PhpUndefinedFieldInspection */
        date_default_timezone_set($this->config->timezone);
    }

    /**
     * [setFactory description]
     * @param    [type]                   $factory [description]
     */
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

        $this->factory = $factory;
        $this->setConfig();
        $this->_initializeLogger();
    }

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

    public function run(array $params = [])
    {
        switch ($this->factory) {
            case 'web':
                $this->_runMvcApplication($params);
                break;
            case 'cli':
                $this->_runCliConsole($params);
                break;
            default:
                $this->_runMvcApplication($params);
                break;
        }
    }

    /**
     * _initializeLogger
     * @return   [type]                   [description]
     */
    private function _initializeLogger()
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

    /**
     * run Phalcon Mvc Application
     * @param    array                    $params [description]
     * @return   [type]                           [description]
     */
    private function _runMvcApplication(array $params)
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

    /**
     * run with phalcon cli console
     * @param    array                    $params [description]
     */
    private function _runCliConsole(array $params)
    {
        $console = new \Phalcon\CLI\Console($this->di());
        $this->di()->setShared('console', $console);

        $arguments = [];
        foreach ($params as $k => $arg) {
            if ($k == 1) {
                $arguments['task'] = $arg;
            } elseif ($k == 2) {
                $arguments['action'] = $arg;
            } elseif ($k >= 3) {
                $arguments['params'][] = $arg;
            }
        }

        define('CURRENT_TASK', (isset($params[1]) ? $params[1] : null));
        define('CURRENT_ACTION', (isset($params[2]) ? $params[2] : null));

        try {
            $console->handle($arguments);
        } catch (\Phalcon\Exception $e) {
            echo $e->getMessage();
            exit(255);
        }
    }
}
