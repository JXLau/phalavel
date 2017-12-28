<?php 

use App\Jobs\News;
use Carbon\Carbon;
use Phalavel\Queues\QueueTask as Queue;

/**
 * 队列任务 
 */
class QueueTask extends Queue
{
	public function workAction(array $args = [])
	{
		parent::workAction($args);
	}

	public function sampleAction()
	{
		$text = '队列延迟';
		$job = (new News($text))->delay(2);
		dispatch($job);
	}
}