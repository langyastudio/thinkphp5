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

	// 查询 - where方法
	public function QueryWhere()
	{
		// AND
		dump(Db::name('user')
				->where('id', '>', 3)
				->where('email', 'LIKE', '123%')
				->select()
		);
	}

	// 查询 - where方法
	public function QueryWhereOr()
	{
		// or
		dump(Db::name('user')
				->where('id', '>', 3)
				->whereOr('email', 'LIKE', '123%')
				->select()
		);
	}

	// 查询 - 混合查询
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

	//  连贯操作		作用								支持的参数类型
	//  where*		用于AND查询						字符串、数组和对象
	//  whereOr*	用于OR查询						字符串、数组和对象
	//  wheretime*	用于时间日期的快捷查询				字符串
	//  table		用于定义要操作的数据表名称			字符串和数组
	//  alias		用于给当前数据表定义别名				字符串
	//  field*		用于定义要查询的字段（支持字段排除）	字符串和数组
	//  order		用于对结果排序						字符串和数组
	//  limit		用于限制查询结果数量				字符串和数字
	//  page		用于查询分页（内部会转换成limit）		字符串和数字
	//  group		用于对查询的group支持				字符串
	//  having		用于对查询的having支持				字符串
	//  join*		用于对查询的join支持				字符串和数组
	//  union*		用于对查询的union支持				字符串、数组和对象
	//  view		用于视图查询						字符串、数组
	//  distinct	用于查询的distinct支持				布尔值
	//  lock		用于数据库的锁机制					布尔值
	//  cache		用于查询缓存						支持多个参数
	//  relation	用于关联查询						字符串
	//  with		用于关联预载入						字符串、数组
	//  bind*		用于数据绑定操作					数组或多个参数
	//  comment		用于SQL注释						字符串
	//  force		用于数据集的强制索引				字符串
	//  master		用于设置主服务器读取数据				布尔值
	//  strict		用于设置是否严格检测字段名是否存在		布尔值
	//  sequence	用于设置Pgsql的自增序列名			字符串
	//  failException	用于设置没有查询到数据是否抛出异常	布尔值
	//  partition		用于设置分表信息				数组 字符串
	public function ChainOper()
	{

	}

	// 聚合查询
	//	count	统计数量，参数是要统计的字段名（可选）
	//	max		获取最大值，参数是要统计的字段名（必须）
	//	min		获取最小值，参数是要统计的字段名（必须）
	//	avg		获取平均值，参数是要统计的字段名（必须）
	//	sum		获取总分，参数是要统计的字段名（必须）
	public function Aggregation()
	{
		$rtn = Db::name('user')->where('email', 'not null')->count();
		return $rtn;
	}

	// 视图查询
	// INNER LEFT RIGHT FULL
	public function ViewQuery()
	{
		$data = Db::view('User', ['name' => 'username', 'pwd'])
				->view('Role', ['name' => 'rolename', 'sort'], 'User.id = Role.userid', 'LEFT')
				->select();

		dump( $data );
	}

	// 时间查询
	// 支持的时间类型包括timestamps、datetime、date和int
	public function TimeQuery()
	{
		$rtn = Db::name('user')->whereTime('create_time', '>', '2016-01-11')->count();
		return $rtn;
	}

	// 子查询
	//Todo：
	public function SubQuery()
	{
		$subQuery = Db::name('ts_user')->where('id', '<=', '17')->select(false);

		$data = Db::table($subQuery.' a')->where('a.email', 'LIKE', '12315%')->select();
		dump($data);
	}
}