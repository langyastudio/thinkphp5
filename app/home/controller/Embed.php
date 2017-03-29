<?php
namespace app\home\controller;

/**
 * Embed
 *
 * @package app\home\controller
 */
class Embed
{
    public function Pic($fid='')
    {
		$data = ['fid' => $fid];

		return view('embed/Pic', $data);
    }

	public function SewiseVideo($fid='')
	{
		$data = ['fid' => $fid];
		return view('embed/SewiseVideo', $data);
	}

	public function Jplayer()
	{
		return view('embed/Jplayer');
	}

	public function VideoJs()
	{
		return view('embed/VideoJs');
	}
}