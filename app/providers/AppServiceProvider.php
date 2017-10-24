<?php
/**
 * Author: Jason
 */
namespace App\Providers;

use App\Foundation\ServiceProvider;
use Exception;
use Phalavel\Queues\QueueManager;
use Phalcon\Cache\Backend\File as BackFile;
use Phalcon\Cache\Backend\Libmemcached as BackMemCached;
use Phalcon\Cache\Backend\Redis as BackRedis;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Cache\Frontend\Output as FrontOutput;
use Predis\Client as Predis;

/**
 * This will always run
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    protected function register()
    {
        $this->registerCache();
        $this->registerDatabase();
        $this->registerQueue();
        $this->redisterModelsCache();
    }

    protected function registerCache()
    {
        $this->di->setShared('cache', function () {
            $config = config('cache');

            switch ($config->driver) {
                case 'memcached':
                    $cache = new BackMemCached(
                        new FrontData(["lifetime" => 7 * 24 * 3600]),
                        ["servers" => $config->memcached->toArray()]
                    );
                    break;
                case 'file':
                    $cache = new BackFile(
                        new FrontOutput(["lifetime" => 6 * 3600]),
                        ['cacheDir' => $config->file->dir]
                    );
                    break;
                case 'redis':
                    //使用redis
                    $cache = new BackRedis(
                        new FrontData(["lifetime" => 7 * 24 * 3600]),
                        $config->redis->toArray()
                    );
                    break;
                default:
                    throw new Exception('no cache driver defined.');
            }

            return $cache;
        });
    }

    protected function registerDatabase()
    {
        $config = $this->config->database;

        foreach ($config as $database => $db_config) {
            $this->di->setShared($database, function () use ($db_config) {
                $dbAapter = 'Phalcon\Db\Adapter\Pdo\\'.$db_config->adapter;
                $connection = new $dbAapter($db_config->toArray());

                return $connection;
            });
        }
    }

    protected function registerQueue()
    {
        $config = $this->config;

        if ($config->queue->driver == 'redis') {
            $this->di->setShared('redis', function() use ($config) {
                $redis = new Predis(
                    $config->cache->redis->toArray()
                );

                return $redis;
            });
        }

        $this->di->set('queue', function () use ($config) {
            $queueManager = new QueueManager($config);
            return $queueManager->getQueue();
        });
    }

    protected function redisterModelsCache()
    {
        $this->di->setShared('modelsCache', $this->di->get('cache'));
    }
}
