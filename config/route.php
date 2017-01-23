<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// + Route::rule('路由表达式','路由地址','请求类型','路由参数（数组）','变量规则（数组）');
// +----------------------------------------------------------------------

//	路由参数（数组）
//		参数				说明
//		method			请求类型检测，支持多个请求类型
//		ext				URL后缀检测，支持匹配多个后缀
//		deny_ext		URL禁止后缀检测，支持匹配多个后缀
//		https			检测是否https请求
//		domain			域名检测
//		before_behavior	前置行为（检测）
//		after_behavior	后置行为（执行）
//		callback		自定义检测方法
//		merge_extra_vars	合并额外参数
//		bind_model			绑定模型（V5.0.1+）
//		cache				请求缓存（V5.0.1+）
//		param_depr			路由参数分隔符（V5.0.2+）
//		ajax				Ajax检测（V5.0.2+）
//		pjax				Pjax检测（V5.0.2+）

return [
		// 首页
		'/'			=> 'home/html/index',
		// home 模块
		'[home]' 	=> [
			':c/:a'=> ['home/:c/:a'],
		],
		// control -> action
		':control/:action'		=> 'home/:control/:action',

		// 全局MISS路由
		'__miss__'  => '/',


//    	'[hello]'     => [
//        	':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        	':name' => ['index/hello', ['method' => 'post']],
//    	],
];
