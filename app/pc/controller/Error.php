<?php
namespace app\pc\controller;

use think\Controller;
use think\facade\Request;

/**
 * 【空控制器】
 *
 * 定制错误页面和进行URL的优化
 *
 * @package app\index\controller
 */
class Error extends Controller
{
	// 空操作
	public function _empty()
	{
		$controller = $this->request->controller();
		$action 	= $this->request->action();
		return $controller . '/' . $action;
	}

	// index 方法
	public function index(Request $request)
	{
		$controller = $request->controller();
		$action 	= $request->action();
		return $controller . '/' . $action;
	}
}