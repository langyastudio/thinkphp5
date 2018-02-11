<?php
namespace app\pc\controller;
use think\Controller;
use think\facade\Request;

/**
 * Index
 *
 * @package app\pc\controller
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
		$data = ['title' => 'ThinkPHP5.0'];

		// [模块@][控制器/][操作]
		return view('index/index', $data);
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
		$this->redirect('index/index');
	}

	// 请求
	public function Request()
	{
		$request = Request::instance();

		//【URL信息】
		echo '<br/>'.'======================= URL信息 ====================' . '<br/>';
		// 获取当前域名
		echo 'domain: ' . $request->domain() . '<br/>';
		// 获取当前入口文件
		echo 'file: ' . $request->baseFile() . '<br/>';
		// 获取当前URL地址 不含域名
		echo 'url: ' . $request->url() . '<br/>';
		// 获取包含域名的完整URL地址
		echo 'url with domain: ' . $request->url(true) . '<br/>';
		// 获取当前URL地址 不含QUERY_STRING
		echo 'url without query: ' . $request->baseUrl() . '<br/>';
		// 获取URL访问的ROOT地址
		echo 'root:' . $request->root() . '<br/>';
		// 获取URL访问的ROOT地址
		echo 'root with domain: ' . $request->root(true) . '<br/>';
		// 获取URL地址中的PATH_INFO信息
		echo 'pathinfo: ' . $request->pathinfo() . '<br/>';
		// 获取URL地址中的PATH_INFO信息 不含后缀
		echo 'pathinfo: ' . $request->path() . '<br/>';
		// 获取URL地址中的后缀信息
		echo 'ext: ' . $request->ext() . '<br/>';

		//【模块/控制器/操作名称】
		echo '<br/>'.'======================= 模块/控制器/操作名称 ====================' . '<br/>';
		echo "当前模块名称是" . $request->module(). '<br/>';
		echo "当前控制器名称是" . $request->controller(). '<br/>';
		echo "当前操作名称是" . $request->action(). '<br/>';

		//【请求参数】
		//方法		描述
		//param		获取当前请求的变量
		//get		获取 $_GET 变量
		//post		获取 $_POST 变量
		//put		获取 PUT 变量
		//delete	获取 DELETE 变量
		//session	获取 $_SESSION 变量
		//cookie	获取 $_COOKIE 变量
		//request	获取 $_REQUEST 变量
		//server	获取 $_SERVER 变量
		//env		获取 $_ENV 变量
		//route		获取 路由（包括PATHINFO） 变量
		//file		获取 $_FILES 变量
		echo '<br/>'.'======================= 请求类型 ====================' . '<br/>';
		// 是否为 GET 请求
		if ($request->isGet()) echo "当前为 GET 请求";
		// 是否为 POST 请求
		if ($request->isPost()) echo "当前为 POST 请求";
		// 是否为 PUT 请求
		if ($request->isPut()) echo "当前为 PUT 请求";
		// 是否为 DELETE 请求
		if ($request->isDelete()) echo "当前为 DELETE 请求";
		// 是否为 Ajax 请求
		if ($request->isAjax()) echo "当前为 Ajax 请求";
		// 是否为 Pjax 请求
		if ($request->isPjax()) echo "当前为 Pjax 请求";
		// 是否为手机访问
		if ($request->isMobile()) echo "当前为手机访问";
		// 是否为 HEAD 请求
		if ($request->isHead()) echo "当前为 HEAD 请求";
		// 是否为 Patch 请求
		if ($request->isPatch()) echo "当前为 PATCH 请求";
		// 是否为 OPTIONS 请求
		if ($request->isOptions()) echo "当前为 OPTIONS 请求";
		// 是否为 cli
		if ($request->isCli()) echo "当前为 cli";
		// 是否为 cgi
		if ($request->isCgi()) echo "当前为 cgi";

		echo '<br/>'.'======================= 请求参数 ====================' . '<br/>';
		echo '请求方法：' . $request->method() . '<br/>';
		echo '资源类型：' . $request->type() . '<br/>';
		echo '访问地址：' . $request->ip() . '<br/>';

		echo '请求参数：';
		//GET、POST或者PUT请求
		dump($request->param());
		//获取部分变量
		echo '请求参数：仅包含name';
		dump($request->only(['name']));
		//排除部分变量
		echo '请求参数：排除name';
		dump($request->except(['name']));
		echo '路由信息：';
		dump($request->route());
		echo '调度信息：';
		dump($request->dispatch());

		echo '<br/>'.'======================= 设置请求参数 ====================' . '<br/>';
		// 更改GET变量
		// 不能直接修改param变量
		$request->get(['id' => 10]);
		dump($request->param());

		echo '<br/>'.'======================= HTTP头信息 ====================' . '<br/>';
		echo 'HTTP信息：';
		dump($request->header());
	}
}