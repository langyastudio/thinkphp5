<?php
namespace app\home\controller\base;

/**
 * HTML Dom / Browse
 *
 * @package app\home\controller
 */
class Dom
{
    public function Document()
    {
		return view('dom/document');
    }

    public function Navigator()
    {
		return view('dom/navigator');
    }
}