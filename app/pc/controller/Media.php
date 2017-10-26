<?php
namespace app\pc\controller;

/**
 * 音频和视频、幻灯片和轮播图、图片展示、图像、地图、滑块和旋转
 */
class Media
{
    public function Pic($fid='')
    {
        $data = ['fid' => $fid];

        return view('media/pic', $data);
    }

    public function Jplayer()
    {
        return view('media/jplayer');
    }

    public function Jwplayer($fid='')
    {
        $data = ['fid' => $fid];
        return view('media/jwplayer', $data);
    }

    public function GeoCoder()
    {
        return view('media/geocoder');
    }

    public function VR()
    {
        return view('media/vr');
    }

    public function FlexSlider()
    {
        return view('media/flexslider');
    }

    public function LazyLoad()
    {
        return view('media/lazyload');
    }

}