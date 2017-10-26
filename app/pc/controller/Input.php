<?php
namespace app\pc\controller;

/**
 * 拾色器、定制和风格、日期和时间、拖和放、通用输入、自动完成、密码、投票率、搜索、选择框、快捷键、触摸、丰富的输入、上传、验证
 */
class Input
{
    public function Clipboard()
    {
        return view('input/clipboard');
    }

    public function UMeditor()
    {
        return view('input/umeditor');
    }

    public function Select2()
    {
        return view('input/select2');
    }

    public function ImageCropper()
    {
        return view('input/imagecropper');
    }
}