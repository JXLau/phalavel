<?php
/**
 * Author: Jason
 */

namespace App\Providers;

use App\Foundation\ServiceProvider;
use Sid\Phalcon\Cron\Manager as CronManager;

/**
 * Runs only in the command line environment
 * @package App\Providers
 */
class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    protected function register()
    {
        $this->registerCron();
    }

    protected function registerCron()
    {
        $crons = $this->getCrons();

        $this->di->set(
            "cron",
            function () use ($crons) {
                $cronManager = new CronManager();

                foreach ($crons as $cron) {
                    switch ($cron['job']) {
                        case 'phalcon':
                            $cmds = explode(' ', $cron['command']);
                            list($task, $action) = $cmds;
                            $params = ' ' . implode(' ', array_slice($cmds, 2));
                            $command = [];
                        	$command['task'] = $task;
                        	$command['action'] = $action;
                        	$command['params'] = $params;
                            break;
                        case 'system':
                            $command = $cron['command'];
                            break;
                        default:
                            throw new \Exception("Do not have " . $cron['job'] . " handler");
                            break;
                    }

                    $job = "\\Sid\\Phalcon\\Cron\\Job\\" . ucfirst($cron['job']);

                    $cronManager->add(new $job($cron['expression'], $command));
                }
                return $cronManager;
            }
        );
    }

    protected function getCrons()
    {
        return require CONFIG_PATH . '/crons.php';
    }
}
