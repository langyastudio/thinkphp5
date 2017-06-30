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

		return view('embed/pic', $data);
    }

	public function Jplayer()
	{
		return view('embed/jplayer');
	}

	public function Jwplayer($fid='')
	{
		$data = ['fid' => $fid];
		return view('embed/jwplayer', $data);
	}
}