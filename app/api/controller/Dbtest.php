<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Request;

/**
 * Index
 *
 * @package app\home\controller
 */
class Dbtest extends Controller
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
		try
		{
			// 启动事务
			Db::startTrans();
			// 支持query（查询操作）和execute（写入操作）
			$num = Db::execute('insert into ts_user (name, pwd) values(:name, :pwd)', ['name' => 'admin', 'pwd' => '123456']);
			// 提交事务
			Db::commit();
			return $num;
		} catch (\Exception $e)
		{
			// 回滚事务
			Db::rollback();
		}
	}
}