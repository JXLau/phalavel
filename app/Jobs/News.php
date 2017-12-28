<?php 
namespace App\Jobs;
use Phalavel\Queues\Job;
use Phalavel\Queues\Queueable;

class News extends Job
{
    use Queueable;

    protected $text;
    
    function __construct($text)
    {
        $this->text = $text;
    }

    public function handle()
    {
        //queue do
    	echo $this->text . PHP_EOL;
    }
}