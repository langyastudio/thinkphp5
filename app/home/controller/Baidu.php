<?php
namespace app\home\controller;

/**
 * Baidu 相关应用
 *
 * @package app\home\controller
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