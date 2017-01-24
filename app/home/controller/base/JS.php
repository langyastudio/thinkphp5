<?php
namespace app\home\controller\base;

/**
 * JS
 *
 * @package app\home\controller
 */
class JS
{
	public function Index()
	{
		return view('js/Index');
	}

	public function Cookie()
    {
		return view('js/Cookie');
    }
}