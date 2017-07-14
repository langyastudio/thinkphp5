<?php
namespace app\server\controller;

use Workerman\Worker;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;

/**
 * Gatewayworker 长链接服务
 *
 * @package app\pc\controller
 */
class Gatewayworker
{
	public function __construct()
	{
		//【1】bussinessWorker 进程
		$worker = new BusinessWorker();
		// worker名称
		$worker->name = 'HacfinCloud BusinessWorker';
		// bussinessWorker进程数量
		$worker->count = 4;
		// 服务注册地址
		$worker->registerAddress = '127.0.0.1:1236';

		//【2】 register 服务必须是text协议
		$register = new Register('text://0.0.0.0:1236');

		//【3】gateway 进程
		$gateway = new Gateway("websocket://0.0.0.0:2346");
		// gateway名称，status方便查看
		$gateway->name = 'HacfinCloud Gateway';
		// gateway进程数
		$gateway->count = 4;
		// 本机ip，分布式部署时使用内网ip
		$gateway->lanIp = '127.0.0.1';
		// 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
		// 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口
		$gateway->startPort = 2900;
		// 服务注册地址
		$gateway->registerAddress = '127.0.0.1:1236';

		// 心跳间隔
//		$gateway->pingInterval = 60;
//		$gateway->pingNotResponseLimit = 2;
		// 心跳数据
//		$gateway->pingData = '';

		// 当客户端连接上来时，设置连接的onWebSocketConnect，即在websocket握手时的回调
		//		$gateway->onConnect = function($connection)
		//		{
		//			$connection->onWebSocketConnect = function($connection , $http_header)
		//			{
		//				// 可以在这里判断连接来源是否合法，不合法就关掉连接
		//				// $_SERVER['HTTP_ORIGIN']标识来自哪个站点的页面发起的websocket链接
		//				if($_SERVER['HTTP_ORIGIN'] != 'http://kedou.workerman.net')
		//				{
		//					$connection->close();
		//				}
		//				// onWebSocketConnect 里面$_GET $_SERVER是可用的
		//				// var_dump($_GET, $_SERVER);
		//			};
		//		};

		Worker::runAll();
	}
}