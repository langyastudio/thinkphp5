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
		return view('html/AV');
    }

    public function Storage()
    {
		return view('html/Storage');
    }

	public function Icon()
	{
		return view('html/Icon');
	}
}