<?php
namespace app\home\controller\base;

/**
 * JS
 *
 * @package app\home\controller
 */
class Jscript
{
	public function Index()
	{
		return view('jscript/Index');
	}

	public function Cookie()
    {
		return view('jscript/Cookie');
    }
}