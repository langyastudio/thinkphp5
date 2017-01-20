<?php
namespace app\home\controller;

/**
 * Html
 *
 * @package app\home\controller
 */
class Html
{
	public function Index()
	{
		return view('html/Index');
	}

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