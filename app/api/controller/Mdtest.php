<?php
namespace app\api\controller;
use app\api\model\User;
use think\Controller;
use think\facade\Debug;
use think\Loader;

/**
 * MdTest 模型测试
 *
 * @package app\pc\controller
 */
class Mdtest extends Controller
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

	// 单例模式
	public function Single()
	{
		// 使用 Loader 类实例化（单例）
		$user = Loader::model('user');
	}

	// 添加数据
	public function Add()
	{
		$data = [
				'name'  => 'XIAOPANGZI',
				'pwd'   => '789456',
				'email' => 'xiappangzi@qq.com'
		];

		$user = new User();
		$user->isUpdate(false)->save($data); //批量触发修改器 可以使用saveAll写入多条

		echo $user->id . '<br/>'; // 注意这里其实是获取模型的主键
		echo $user->create_time;
	}

	// 更新数据
	public function Update()
	{
		$data = [
				'name'  => 'updatedata',
				'pwd'   => '456369',
				'email' => 'updatedata@qq.com'
		];

		$user = new User();
		return $user->isUpdate(true)->save($data, ['id' => 9]);
	}

	// 删除数据
	public function Delete()
	{
		return User::destroy(['id' => 29]);
	}

	// 查询
	public function Query()
	{
		Debug::remark('s');

		// 默认不包含
		// withTrashed 包含软删除的数据
		// onlyTrashed 仅仅查询软删除的数据
		$list = User::withTrashed()->where('id', '>', '10')
				->order('id', 'desc')
				->limit(3)
				->select();

		foreach($list as $key => $user){
			echo $user->sex; // 通过获取器获取字段
			dump($user->getData()); // getData 获取原始字段数据
		}

		Debug::remark('e');

		echo Debug::getRangeTime('s', 'e') . 's';
	}

	// 查询 hidden、visible append
	public function VisibleQuery()
	{
		// toArray 通过获取器获取字段
		dump( User::get(['id' => 30])
				->visible(['name', 'email', 'sex'])
				->toArray() );
	}

	// 聚合
	public function Aggregation()
	{
		$user = new User();

		return $user->where('id', '>', 10)
				->count();
	}

}