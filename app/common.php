<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Config;
use Wechat\Loader;

/*
 * API返回的数据拼接成数据
 */
function json_pre($code, $result = null, $msg = null, $count = null, $took = null, $version = null)
{
	if (0 == $code)
	{
		$code = REST_SUCCESS;
	}

	global $E;
	$arr = array();
	$arr['code'] = $code;
	if (isset($msg))
	{
		$arr['msg'] = $msg;
	}

	else
	{
		$arr['msg'] = $E[$code];
	}

	if (isset($took))
	{
		$arr['took'] = $took;
	}

	if (isset($count))
	{
		$arr['count'] = $count;
	}

	if (isset($version))
	{
		$arr['version'] = $version;
	}
	
	if (isset($result))
	{
		$arr['result'] = $result;
	}

	return $arr;
}

// 应用公共文件

/*
 * 或者使用UNIX_TIMESTAMP FROM_UNIXTIME
 */
/*
 * php time() 转 mysql
 */
function time_phptomysql($phptime)
{
	return date('Y-m-d H:i:s', $phptime);
}

/*
 * mysql date 转 php time()
 */
function time_mysqltophp($mysqldate)
{
	return strtotime($mysqldate);
}

function ismobile()
{
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
		return true;

	//此条摘自TPM智能切换模板引擎，适合TPM开发
	if (isset ($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'])
		return true;

	//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset ($_SERVER['HTTP_VIA']))
		//找不到为flase,否则为true
		return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;

	//判断手机发送的客户端标志,兼容性有待提高
	if (isset ($_SERVER['HTTP_USER_AGENT']))
	{
		$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-',
				'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront',
				'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp',
				'wap', 'mobile');

		//从HTTP_USER_AGENT中查找手机浏览器的关键字
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
		{
			return true;
		}
	}

	//协议法，因为有可能不准确，放到最后判断
	if (isset ($_SERVER['HTTP_ACCEPT']))
	{
		// 如果只支持wml并且不支持html那一定是移动设备
		// 如果支持wml和html但是wml在html之前则是移动设备
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
		{
			return true;
		}
	}

	return false;
}

/**
 * 获取微信操作对象
 *
 * 可以理解为单例-工厂模式
 *
 * @param string $type
 *
 * @return Wechat.$type
 */
function & load_wechat($type = '')
{
	static $wechat = array();
	$index = md5(strtolower($type));

	if (!isset($wechat[$index]))
	{
		//公众号配置文件
		$options = Config::get('wechat');
		$options['cachepath'] = CACHE_PATH . 'data/';

		$wechat[$index] = &Loader::get($type, $options);
	}

	return $wechat[$index];
}