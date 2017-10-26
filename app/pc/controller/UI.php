<?php
namespace app\pc\controller;

/**
 * 背景、对话框/灯箱、筛选及排序、反馈、弹出层、悬停、布局、图表、加载、圆边、滚动、标签、文本链接、工具提示、网络类型
 */
class UI
{
    public function MagnificPopup()
    {
        return view('ui/magnificpopup');
    }

    public function Toastr()
    {
        return view('ui/toastr');
    }

    public function Nprogress()
    {
        return view('ui/nprogress');
    }

    public function WayPoints()
    {
        return view('ui/waypoints');
    }

    public function Layer()
    {
        return view('ui/layer');
    }

    public function ScrollUp()
    {
        return view('ui/scrollup');
    }

    public function LayPage()
    {
        return view('ui/laypage');
    }
}