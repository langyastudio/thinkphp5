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
		return view('plugin/clipboard');
	}

	public function Md5File()
	{
		return view('plugin/md5file');
	}

	public function RequireJs()
	{
		return view('plugin/requirejs');
	}

    public function ImageCropper()
    {
		return view('plugin/imagecropper');
    }

    public function Layer()
    {
		return view('plugin/layer');
    }    

    public function ScrollUp()
    {
		return view('plugin/scrollup');
    }

    public function FlexSlider()
    {
		return view('plugin/flexslider');
    }

    public function Hash()
    {
		return view('plugin/hash');
    }

    public function WayPoints()
    {
		return view('plugin/waypoints');
    }

    public function LazyLoad()
    {
		return view('plugin/lazyload');
    }

    public function Transit()
    {
		return view('plugin/transit');
    }

	public function Nprogress()
	{
		return view('plugin/nprogress');
	}

	public function Toastr()
	{
		return view('plugin/toastr');
	}

	public function JSTree()
	{
		return view('plugin/jstree');
	}

	public function Select2()
	{
		return view('plugin/select2');
	}

	public function Bowser()
	{
		return view('plugin/bowser');
	}

	public function MagnificPopup()
	{
		return view('plugin/magnificpopup');
	}

	public function LayPage()
	{
		return view('plugin/laypage');
	}
}