<?php
namespace app\pc\controller;

/**
 * Plugin
 *
 * @package app\pc\controller
 */
class Other
{
    public function RequireJs()
    {
        return view('other/requirejs');
    }

    public function Md5File()
    {
        return view('other/md5file');
    }

    public function Hash()
    {
		return view('other/hash');
    }

	public function Bowser()
	{
		return view('other/bowser');
	}
}