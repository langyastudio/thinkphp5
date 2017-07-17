<?php
namespace app\api\controller;
use crypt\PhpCrypt;
use think\Controller;


/**
 * Index
 *
 *
 * @package app\pc\controller
 */
class Phptest extends Controller
{
	// Closure - 匿名函数
	public function ClosureFun()
	{
		//1.0
		$msg = 'world';

		// Inherited variable's value is from when the function
		// is defined, not when called
		// 可以使用 &
		$greet = function ($name) use ($msg)
		{
			echo $name . $msg;
		};

		$greet('hello');

		$msg = 'word';
		$greet('hello');

		//2.0 call
		$call = function (){
			echo self::$m_php_mcrypt_decrypt_key;
		};
		$call->call(new PhpCrypt());
	}

	// 生成器 -- 简化的迭代器Iterator
	public function YieldFun()
	{
		$xrange = function ($start, $limit, $step = 1)
		{
			if ($start < $limit)
			{
				if ($step <= 0)
				{
					throw new \Exception('Step must be +ve');
				}

				for ($i = $start; $i <= $limit; $i += $step)
				{
					yield $i;
				}
			} else
			{
				if ($step >= 0)
				{
					throw new \Exception('Step must be -ve');
				}

				for ($i = $start; $i >= $limit; $i += $step)
				{
					yield $i;
				}
			}
		};

		foreach ($xrange(2, 8, 2) as $value)
		{
			echo $value;
		}
	}

	public function Inject(int $ivar=1):int
	{
		$a = null;
		$b = $a ?? 8;
		assert(true, 'error');
		return $b;
	}
}