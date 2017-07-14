<?php
namespace app\server\controller;
use think\worker\Server;

/**
 * Worker 长链接服务
 *
 * @package app\pc\controller
 */
class Flashworker extends Server
{
	protected $socket = 'tcp://0.0.0.0:843';

	/**
	 * 收到信息
	 * @param $connection
	 * @param $data
	 */
	public function onMessage($connection, $data)
	{
//		policy-file-request
//		因为flash总是先会从843找，如果找不到再从websocket端口
		$connection->send('<?xml version="1.0"?><cross-domain-policy><site-control permitted-cross-domain-policies="all"/><allow-access-from domain="*" to-ports="*"/></cross-domain-policy>'."\0");
	}

	/**
	 * 当连接建立时触发的回调函数
	 * @param $connection
	 */
	public function onConnect($connection)
	{

	}

	/**
	 * 当连接断开时触发的回调函数
	 * @param $connection
	 */
	public function onClose($connection)
	{

	}

	/**
	 * 当客户端的连接上发生错误时触发
	 * @param $connection
	 * @param $code
	 * @param $msg
	 */
	public function onError($connection, $code, $msg)
	{
		echo "error $code $msg\n";
	}

	/**
	 * 每个进程启动
	 * @param $worker
	 */
	public function onWorkerStart($worker)
	{

	}
}