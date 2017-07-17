<?php
namespace app\pc\controller\base;

/**
 * JS
 *
 * @package app\pc\controller
 */
class Jscript
{
	public function Index()
	{
		return view('jscript/index');
	}

	public function Cookie()
    {
		return view('jscript/cookie');
    }
}