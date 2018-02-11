<?php
namespace app\api\controller;
use think\Controller;
use think\facade\Request;

/*
 * 首先对进入微信网页的用户进入简单的网页授权，获取到用户的openid
 * 通过简单接口获取到的openid，可以查询数据库或者调接口WechatUser::getUserInfo获取用户信息
 * 如果用户参数没有或没有关注，可以再次生成微信Oauth授权链接跳转过去，当然这次需要使用高级模式
 * 当用户确认授权之后，我们就可以使用WechatOauth::getOauthUserInfo接口来拉取用户信息。
 * 注意：在上面环境要记录openid到SESSION，如果有openid就不需要进行第一次授权了
 */
class Wechat extends Controller
{
	/**
	 * 1.0 用户同意授权，获取code
	 *
	 * 创建微信网页授权URL
	 *
	 * @return bool
	 */
	public function OauthRedirect($restart = false)
	{
		// SDK实例对象
		$oauth = & load_wechat('Oauth');

		// 执行接口操作
		$result = $oauth->getOauthRedirect(Request::instance()->domain() . '/api/wechat/oauthinfo', '', 'snsapi_base');

		// 处理返回结果
		if($result===FALSE)
		{
			// 接口失败的处理
			return false;
		}
		else
		{
			ob_end_clean();

			// 接口成功的处理
			\QRcode:: png($result, false, QR_ECLEVEL_L, 5, 0); //生成二维码
		}
	}

	/**
	 * 2.0 通过code换取网页授权access_token
	 * 3.0 获取授权后的用户资料
	 */
	public function OauthInfo()
	{
		echo Request::instance()->request();

//		// SDK实例对象
//		$oauth = & load_wechat('Oauth');
//
//		// 执行接口操作
//		$result = $oauth->getOauthAccessToken();
//
//		// 处理返回结果
//		if($result===FALSE){
//			// 接口失败的处理
//			return false;
//		}else{
//			// 接口成功的处理
//		}
//
//		// SDK实例对象
//		$oauth = & load_wechat('Oauth');
//
//		// 执行接口操作
//		$result = $oauth->getOauthUserinfo($access_token, $openid);
//
//		// 处理返回结果
//		if($result===FALSE){
//			// 接口失败的处理
//			return false;
//		}else{
//			// 接口成功的处理
//		}

	}
}