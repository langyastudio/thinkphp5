<?php
namespace app\pc\controller\base;

/**
 * HTML Dom / Browse
 *
 * @package app\pc\controller
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