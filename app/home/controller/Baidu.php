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
		return view('baidu/Share');
    }

    public function ShareSelf()
    {
		return view('baidu/ShareSelf');
    }

    public function ShareButton()
    {
		return view('baidu/ShareButton');
    }

    public function Upload()
    {
		return view('baidu/Upload');
    }
}