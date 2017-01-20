<?php
define('PAGE_DEFAULT_PAGE', 1);
define('PAGE_DEFAULT_PAGE_COUNT', 20);

define('AUTH_PRODUCTTYPE_NO_EXPIRE', 1);  //授权类型 -- 无过期时间
define('AUTH_PRODUCTTYPE_EXPIRE', 2);  //授权类型 -- 有过期时间

//region------------------------------------------- ERROR Code -------------------------------------------------------//
//所有的错误码用于 API接口
//!!!! 内部自定义的Class的返回码为0时，表示调用成功

//Common - (200, 400)
define('REST_SUCCESS', 200); //没有错误
define('REST_ERROR', 400); 	 //请求错误

//Param - (401 - 419)
define('REST_PARAM_ERROR', 401);//参数错误

//AUTH - (420 - 439)
define('REST_AUTH_NOT_EXIST', 420); //未找到授权信息
define('REST_AUTH_EXIST', 421); //授权信息已存在
define('REST_AUTH_DELETE_ERROR', 422); //授权信息删除失败

//DB - (440 - 459)
define('REST_DATABASE_ACTIONERROR', 440); //数据库执行出错

// Errors 映射
$E = array(
		REST_SUCCESS => '没有错误',
		REST_ERROR   => '请求错误',

		REST_PARAM_ERROR => '参数错误',

		REST_AUTH_NOT_EXIST    => '未找到授权信息',
		REST_AUTH_EXIST        => '授权信息已存在',
		REST_AUTH_DELETE_ERROR => '授权信息删除失败',

		REST_DATABASE_ACTIONERROR => '数据库执行出错',
);
//endregion--------------------------------------------- ERROR END ----------------------------------------------------//