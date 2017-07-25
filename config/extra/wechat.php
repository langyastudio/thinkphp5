<?php

return [
	// +----------------------------------------------------------------------
	// | 开发参数设置
	// +----------------------------------------------------------------------
		'token'          => 'HacfinToken', //填写你设定的token
		'appid'          => 'wx3efd3f0ae00257bb', //填写高级调用功能的app id, 请在微信开发模式后台查询
		'appsecret'      => '1476eeffbcd520e641ea08fde48cc96f', //填写高级调用功能的密钥
		'encodingaeskey' => 'zwQAcq5BLBNHx9IJpwXDWWhXmb176qdfhTSwmQjRBkq', //填写加密用的EncodingAESKey（可选，接口传输选择加密时必需）
		'mch_id'         => '',  //微信支付，商户ID（可选）
		'partnerkey'     => '',  //微信支付，密钥（可选）
		'ssl_cer'        => '',  //微信支付，双向证书（可选，操作退款或打款时必需）
		'ssl_key'        => '',  //微信支付，双向证书（可选，操作退款或打款时必需）
		'cachepath'      => '',  //设置SDK缓存目录（可选，默认位置在./Wechat/Cache下，请保证写权限）
];
