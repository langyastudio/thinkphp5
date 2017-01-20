<?php
namespace app\home\controller;

/**
 * Plugin
 *
 * @package app\home\controller
 */
class Plugin
{
	public function Clipboard()
	{
		return view('plugin/Clipboard');
	}

	public function Md5File()
	{
		return view('plugin/Md5File');
	}

	public function RequireJs()
	{
		return view('plugin/RequireJs');
	}

    public function ImageCropper()
    {
		return view('plugin/ImageCropper');
    }

    public function ImageView()
    {
		return view('plugin/ImageView');
    }

    public function Layer()
    {
		return view('plugin/Layer');
    }    

    public function ScrollUp()
    {
		return view('plugin/ScrollUp');
    }

    public function FlexSlider()
    {
		return view('plugin/FlexSlider');
    }

    public function Hash()
    {
		return view('plugin/Hash');
    }

    public function WayPoints()
    {
		return view('plugin/WayPoints');
    }

    public function LazyLoad()
    {
		return view('plugin/LazyLoad');
    }

    public function Transit()
    {
		return view('plugin/Transit');
    }

	public function Sewise()
	{
		return view('plugin/Sewise');
	}

	public function Jplayer()
	{
		return view('plugin/Jplayer');
	}
}