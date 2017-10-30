<?php
namespace app\api\controller;

use think\{
    Controller, Request
};
use app\api\model\common\UeditorM;

//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
class Ueditor extends Controller
{
    private $_ueditorM = null;
    protected function _initialize()
    {
        $this->_ueditorM = new UeditorM();

        parent::_initialize();
    }

    public function Operate()
    {
        $instance = Request::instance();
        $action = $instance->get('action');

        switch ($action)
        {
            case 'config':{
                $result =  json_encode($this->_ueditorM->GetConfig());
            }
            break;
            /* 上传图片 */
            case 'uploadimage':
            /* 上传涂鸦 */
            case 'uploadscrawl':
            /* 上传视频 */
            case 'uploadvideo':
            /* 上传文件 */
            case 'uploadfile':
            {
                $result = $this->_ueditorM->Upload();
            }
            break;
            /* 列出图片 */
            case 'listimage':
            /* 列出文件 */
            case 'listfile':
            {
                $result = $this->_ueditorM->FileList();
            }
            break;
            case 'catchimage':
            {
                $result = $this->_ueditorM->Crawler();
            }
            break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
}