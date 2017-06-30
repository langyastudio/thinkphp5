<?php
namespace app\home\controller;

/**
 * 地图
 *
 * @package app\home\controller
 */
class AMap
{
    public function GeoCoder()
    {
       return view('amap/geocoder');
    }
}