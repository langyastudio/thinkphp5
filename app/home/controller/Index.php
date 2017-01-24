<?php
namespace app\home\controller;
use think\Controller;

/**
 * Index
 *
 * @package app\home\controller
 */
class Index extends Controller
{
	// 控制器初始化
	public function _initialize()
	{

	}

	// 空操作
	// 定制错误页面和进行URL的优化
	public function _empty()
	{

	}

	public function Index()
	{
		return view('index/Index');
	}

	// 页面成功跳转
	public function JumpSuccess()
	{
		//设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
		$this->success('下载成功');
	}

	// 页面失败跳转
	public function JumpError()
	{
		//错误页面的默认跳转页面是返回前一页，通常不需要设置
		//javascript:history.back(-1)
		$this->error('下载失败');
	}

	// 重定向
	public function PageRedirect()
	{
		$this->redirect('Index/index');
	}
}