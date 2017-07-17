<?php
namespace app\server\controller;
use GatewayWorker\Lib\Gateway;

/**
 * Websocket API
 *
 * @package app\pc\controller
 */
class Interact
{
    public function Bind($client_id)
    {
		$rtn = false;
		try
		{
			$rtn = Gateway::bindUid($client_id, 1);
			//		Gateway::joinGroup($client_id, 2);
		}
		catch (\Exception $e)
		{
			return json( json_pre(REST_ERROR) );
		}

		if ($rtn)
			return json( json_pre(0) );
		return json( json_pre(REST_ERROR) );
    }

	public function Send($client_id, $message)
	{
		try
		{
			Gateway::sendToAll(json_encode(array('type' => 'msg', 'msg' => $client_id . ' 发送到了消息 ' . $message)));
		}
		catch (\Exception $e)
		{
			return json( json_pre(REST_ERROR) );
		}

		return json( json_pre(0) );
	}
}