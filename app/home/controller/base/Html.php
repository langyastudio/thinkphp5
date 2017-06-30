<?php
namespace app\home\controller\base;

/**
 * Html
 *
 * view、xml、json和jsonp
 *
 * @package app\home\controller
 */
class Html
{
    public function AV()
    {
		return view('html/av');
    }

    public function Storage()
    {
		return view('html/storage');
    }

	public function Icon()
	{
		return view('html/icon');
	}
}