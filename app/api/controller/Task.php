<?php
namespace app\api\controller;
use think\Controller;
use Cron\CronExpression;

class Task extends Controller
{
	/**
	 * Task
	 *
	 * @param bool $restart
	 */
	public function ajaxExec($restart = false)
	{
		ignore_user_abort(true);//关掉浏览器，PHP脚本也可以继续执行.
		set_time_limit(0);//通过set_time_limit(0)可以让程序无限制的执行下去
		session_write_close();

		/* Check cron turn on. -- config*/
		//if() die();

		/* Create restart tag file. */
		$restartTag = TEMP_PATH . 'restartcron';
		if($restart) touch($restartTag);

		/* make cron status to running.  -- config */

		/* Get and parse crons. */
		$parsedCronFun = function ($crons)
		{
			$parsedCrons = array();
			foreach($crons as $cron)
			{
				$row = "{$cron['min']} {$cron['hour']} {$cron['day']} {$cron['month']} {$cron['week']} {$cron['command']}";
				preg_match_all('/(\S+\s+){5}|.*/', $row, $matchs);
				if($matchs[0])
				{
					try
					{
						$parsedCron = array();
						$parsedCron['schema']   = trim($matchs[0][0]);
						$parsedCron['command']  = trim($matchs[0][1]);
						$parsedCron['cron']     = CronExpression::factory($parsedCron['schema']);
						$parsedCron['time']     = $parsedCron['cron']->getNextRunDate();
						$parsedCrons[$cron['id']] = $parsedCron;
					}
					catch(\InvalidArgumentException $e)
					{
						/* make cron id status to stop. */
						continue;
					}
				}
			}
			return $parsedCrons;
		};
		$crons = [
				[
						'id'	  => 1,
						'min'     => '*/1', //取值范围:0-59
						'hour'    => '*',//取值范围:0-23
						'day'     => '*',//取值范围:1-31
						'month'   => '*',//取值范围:1-12
						'week'    => '*',//取值范围:0-6
						'command' => 'echo \'test command\'',
						'remark'  => '*',
				]
		];
		$parsedCrons = $parsedCronFun($crons);

		//log
		$logCronFun = function ($log)
		{
			$file = LOG_PATH . 'cron.' . date('Ymd') . '.log.php';
			if (!is_file($file))
				$log = "<?php\n die();\n" . $log;

			$fp = fopen($file, "a");
			fwrite($fp, $log);
			fclose($fp);
		};

		/* Update last time. */
		$startedTime = time();
		while(true)
		{
			/* When cron is null then die. */
			if(empty($crons)) break;
			if(empty($parsedCrons)) break;

			/* Check cron turn on. -- config*/
			//if() break;

			/* Die old process when restart. */
			if(file_exists($restartTag) and !$restart) die(unlink($restartTag));
			//$restart = false;

			/* Run crons. */
			$now = new \DateTime('now');
			foreach($parsedCrons as $id => $cron)
			{
				/* Skip empty and stop cron.*/
				//if() continue;

				/* Skip cron that status is running and run time is less than max. */
				//if() continue;

				/* Skip cron that last time is more than this cron time. */
				//if() die();

				//if($now > $cron['time'])
				{
					/* make cron status to running. */

					$parsedCrons[$id]['time'] = $cron['cron']->getNextRunDate();

					/* Execution command. */
					$output = '';
					$return = '';
					if($cron['command'])
					{
						//comand or exec youself
						exec($cron['command'], $output, $return);
						if($output) $output = join("\n", $output);

						/* Save log. */
						$time = $now->format('G:i:s');
						$log  = "$time task " .  $id . " executed,\ncommand: $cron[command].\nreturn : $return.\noutput : $output\n";
						$logCronFun($log);
						unset($log);
					}

					/* Revert cron id status. - normal */
				}
			}

			/* Check whether the task change. */

			/* Sleep some seconds. */
			$sleepTime = 60 - ((time() - strtotime($now->format('Y-m-d H:i:s'))) % 60);
			sleep($sleepTime);

			/* Break while. */
			if(connection_status() != CONNECTION_NORMAL) break;
		}

		/* Revert cron status to stop. -- config*/
	}
}