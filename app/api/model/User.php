<?php
namespace app\api\model;
use think\Model;
use traits\model\SoftDelete;

class User extends Model
{
//	protected $pk 		= 'id'; //默认主键为自动识别
//	protected $table 	= 'ts_user'; // 模型类的命名规则是除去表前缀的数据表名称，采用驼峰法命名，并且首字母大写，可设置当前模型对应的完整数据表名称
	protected $readonly = ['name','email']; //字段的值一旦写入，就无法更改

	use SoftDelete;
	protected $deleteTime = 'delete_time';

	//自定义初始化
	protected function initialize()
	{
		//需要调用`Model`的`initialize`方法
		parent::initialize();

		//TODO:自定义的初始化
	}

	// 获取器
	public function getSexAttr($value, $data)
	{
		$status = [0 => '男生', 1 => '女生'];
		return $status[$value];
	}

	// 修改器
	public function setNameAttr($value, $data)
	{
		return strtolower($value);
	}
}