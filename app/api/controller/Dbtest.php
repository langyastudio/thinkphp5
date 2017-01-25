<?php
namespace app\api\controller;
use think\Controller;
use think\Db;


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

	// 事务
	public function Trans()
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
		}
		catch (\Exception $e)
		{
			// 回滚事务
			Db::rollback();
		}
	}

	// 基本查询
	public function QueryBase()
	{
		//1.0 基本查询
		//查询数据集
		dump(Db::name('user')
				->where('id', 1)
				->select());

		//查询单条数据
		dump( Db::name('user')
		->where('id', 2)
		->find());
	}

	// 查询列
	public function QueryColumn()
	{
		// 某个字段的值
		dump( Db::name('user')
				->where('id', '<', 4)
				->value('name'));

		// 某个列的数组
		dump( Db::name('user')
		->where('id', '<', 4)
		->column('name'));
	}

	// 查询列 - where方法
	public function QueryWhere()
	{
		// AND
		dump(Db::name('user')
				->where('id', '>', 3)
				->where('email', 'LIKE', '123%')
				->select()
		);
	}

	// 查询列 - where方法
	public function QueryWhereOr()
	{
		// or
		dump(Db::name('user')
				->where('id', '>', 3)
				->whereOr('email', 'LIKE', '123%')
				->select()
		);
	}

	// 查询列 - 混合查询
	public function QueryMix()
	{
		dump(
				Db::name('user')
						->where(function ($query)
						{
							$query->where('id', 1)->whereOr('id', 2);
						})->whereOr(function ($query)
						{
							$query->where('email', 'LIKE', '123%')->whereOr('name', 'LIKE', 'test%');
						})->select()
		);
	}

	// 添加单条数据
	public function InsertOne()
	{
		$data = [
				'name'  => 'jiangjiaxiong',
				'pwd'   => '123456',
				'email' => '12315@qq.com'
		];

		$rtn = Db::name('user')->insert($data);
		return Db::name('user')->getLastInsID();
	}

	// 添加多条
	public function InsertAll()
	{
		$data = [
				[
						'name'  => 'jiangjiaxiong',
						'pwd'   => '123456',
						'email' => '12315@qq.com'
				],
				[
						'name'  => 'testuser',
						'pwd'   => '123456',
						'email' => 'test@qq.com'
				]
		];

		$rtn = Db::name('user')->insertAll($data);

		return json($rtn);
	}

	// 更新数据表中的数据
	public function UpdateOne()
	{
		$rtn = Db::name('user')
				->where('id', 1)
				->update([
						'name'        => 'nameupdate',
						'create_time' => ['exp', 'now()']
				]);

		return json($rtn);
	}

	// 自增或自减一个字段的值
	public function UpdateInc()
	{
		$rtn = Db::name('user')
				->where('id', 1)
				->setInc('sex'); //sex 本身必须是一个int值，不能是NULL （setDec）

		return json($rtn);
	}

	public function Delete()
	{
		$rtn = Db::name('user')
				->delete('10, 11, 12');

		return json($rtn);
	}

	public function Aggregation()
	{

	}

	public function ViewQuery()
	{

	}

	public function SubQuery()
	{

	}
}