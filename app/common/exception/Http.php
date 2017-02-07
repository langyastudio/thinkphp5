<?php
namespace app\common\exception;
use think\exception\Handle;
use think\exception\HttpException;

/*
 * 异常处理接管
 */
class Http extends Handle
{
	public function render(\Exception $e)
	{
		if ($e instanceof HttpException) {
			$statusCode = $e->getStatusCode();
		}
		//TODO::开发者对异常的操作

		//可以在此交由系统处理
		return parent::render($e);
	}
}