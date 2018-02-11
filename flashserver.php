<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//强制模式，返回值要求是float，返回int就强制类型转换
//严格模式 返回值要求是float，返回int就报错
declare(strict_types=0);

namespace think;

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/app/');

// 定义配置文件目录和应用目录同级
define('CONF_PATH', __DIR__.'/config/extra/');

// 引入自定义配置文件
require CONF_PATH . 'basdefine.php';

// 加载基础文件
require __DIR__ . '/thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->path(APP_PATH)->bind('server/Flashworker')->run()->send();