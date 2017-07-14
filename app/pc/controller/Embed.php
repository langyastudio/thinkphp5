<?php
namespace app\pc\controller;

/**
 * Embed
 *
 * @package app\pc\controller
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