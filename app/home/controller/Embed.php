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

	public function Jplayer()
	{
		return view('embed/Jplayer');
	}

	public function Jwplayer($fid='')
	{
		$data = ['fid' => $fid];
		return view('embed/Jwplayer', $data);
	}
}