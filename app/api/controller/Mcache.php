<?php
namespace app\api\controller;

use app\api\model\User;
use think\facade\Cache;
use think\Controller;
use think\Db;

class Mcache extends Controller
{
	public function Insert($name='')
	{
		Db::table('ts_user')
				->cache($name,60)
				->find();
		$data = Cache::get( $name );

		// 更新与删除操作自动删除缓存
		$result = Db::table('ts_user')
				->cache($name)
				->where(['username' => 'abc'])
				->update(['pwd' => '123456' . time()]);
		$data = Cache::get( $name );

		halt( $result . $data );
	}

	public function Get($name='')
	{
		$user = new User();

		$data = $user->where('username', $name)->value('pwd');

		halt($data);
	}
}