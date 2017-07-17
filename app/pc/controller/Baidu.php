<?php
namespace app\pc\controller;

/**
 * Baidu 相关应用
 *
 * @package app\pc\controller
 */
class Baidu
{
    public function Share()
    {
		return view('baidu/share');
    }

    public function ShareSelf()
    {
		return view('baidu/shareself');
    }

    public function ShareButton()
    {
		return view('baidu/sharebutton');
    }

    public function Upload()
    {
		return view('baidu/upload');
    }
}