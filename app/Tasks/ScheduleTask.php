<?php

use App\Facades\Cache;
use App\Models\Users;
use Phalcon\Cli\Task;

class ScheduleTask extends Task
{
    public function runAction()
    {
        $this->cron->runInBackground();
    }

    public function killAction()
    {
        $this->cron->kill();
    }

    public function testAction($param = [])
    {
        echo "ScheduleTask test " . json_encode($param) . ' time ' . time() . PHP_EOL;
    }
}