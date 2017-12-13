<?php
//强制模式，返回值要求是float，返回int就强制类型转换
//严格模式 返回值要求是float，返回int就报错
declare(strict_types=0);

// 定义应用目录
define('APP_PATH', __DIR__ . '/app/');

// 定义配置文件目录和应用目录同级
define('CONF_PATH', __DIR__.'/config/');

// 引入自定义配置文件
require  __DIR__ . '/config/basdefine.php';

// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';