<?php
namespace app\api\model\common;

use ueditor\Uploader;

class UeditorM
{
    private $_CONFIG = [];

    public function __construct()
    {
        $this->_CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(CONF_PATH . 'ueditor.json')), true);
    }

    public function GetConfig()
    {
        return $this->_CONFIG;
    }

    public function Upload()
    {
        $sorConfig = $this->_CONFIG;

        /* 上传配置 */
        $base64 = "upload";
        switch (htmlspecialchars($_GET['action']))
        {
            case 'uploadimage':
                $config    = array(
                    "pathFormat" => $sorConfig['imagePathFormat'],
                    "maxSize"    => $sorConfig['imageMaxSize'],
                    "allowFiles" => $sorConfig['imageAllowFiles']
                );
                $fieldName = $sorConfig['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config    = array(
                    "pathFormat" => $sorConfig['scrawlPathFormat'],
                    "maxSize"    => $sorConfig['scrawlMaxSize'],
                    "allowFiles" => $sorConfig['scrawlAllowFiles'],
                    "oriName"    => "scrawl.png"
                );
                $fieldName = $sorConfig['scrawlFieldName'];
                $base64    = "base64";
                break;
            case 'uploadvideo':
                $config    = array(
                    "pathFormat" => $sorConfig['videoPathFormat'],
                    "maxSize"    => $sorConfig['videoMaxSize'],
                    "allowFiles" => $sorConfig['videoAllowFiles']
                );
                $fieldName = $sorConfig['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config    = array(
                    "pathFormat" => $sorConfig['filePathFormat'],
                    "maxSize"    => $sorConfig['fileMaxSize'],
                    "allowFiles" => $sorConfig['fileAllowFiles']
                );
                $fieldName = $sorConfig['fileFieldName'];
                break;
        }

        /* 生成上传实例对象并完成上传 */
        $up = new Uploader($fieldName, $config, $base64);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */

        /* 返回数据 */

        return json_encode($up->getFileInfo());
    }

    public function Crawler()
    {
        $sorConfig = $this->_CONFIG;

        /* 上传配置 */
        $config    = array(
            "pathFormat" => $sorConfig['catcherPathFormat'],
            "maxSize"    => $sorConfig['catcherMaxSize'],
            "allowFiles" => $sorConfig['catcherAllowFiles'],
            "oriName"    => "remote.png"
        );
        $fieldName = $sorConfig['catcherFieldName'];

        /* 抓取远程图片 */
        $list = array();
        if (isset($_POST[$fieldName]))
        {
            $source = $_POST[$fieldName];
        }
        else
        {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl)
        {
            $item = new Uploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, array(
                "state"    => $info["state"],
                "url"      => $info["url"],
                "size"     => $info["size"],
                "title"    => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source"   => htmlspecialchars($imgUrl)
            ));
        }

        /* 返回抓取数据 */

        return json_encode(array(
            'state' => count($list) ? 'SUCCESS' : 'ERROR',
            'list'  => $list
        ));
    }

    public function FileList()
    {
        $sorConfig = $this->_CONFIG;

        /* 判断类型 */
        switch ($_GET['action'])
        {
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $sorConfig['fileManagerAllowFiles'];
                $listSize   = $sorConfig['fileManagerListSize'];
                $path       = $sorConfig['fileManagerListPath'];
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowFiles = $sorConfig['imageManagerAllowFiles'];
                $listSize   = $sorConfig['imageManagerListSize'];
                $path       = $sorConfig['imageManagerListPath'];
        }
        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size  = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end   = $start + $size;

        /* 获取文件列表 */
        $path  = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "" : "/") . $path;
        $files = $this->Getfiles($path, $allowFiles);
        if (!count($files))
        {
            return json_encode(array(
                "state" => "no match file",
                "list"  => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--)
        {
            $list[] = $files[$i];
        }
        //倒序
        //for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
        //    $list[] = $files[$i];
        //}

        /* 返回数据 */
        $result = json_encode(array(
            "state" => "SUCCESS",
            "list"  => $list,
            "start" => $start,
            "total" => count($files)
        ));

        return $result;
    }

    /**
     * 遍历获取目录下的指定类型的文件
     *
     * @param       $path
     * @param array $files
     *
     * @return array
     */
    protected function Getfiles($path, $allowFiles, &$files = array())
    {
        if (!is_dir($path))
            return null;
        if (substr($path, strlen($path) - 1) != '/')
            $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle)))
        {
            if ($file != '.' && $file != '..')
            {
                $path2 = $path . $file;
                if (is_dir($path2))
                {
                    $this->Getfiles($path2, $allowFiles, $files);
                }
                else
                {
                    if (preg_match("/\.(" . $allowFiles . ")$/i", $file))
                    {
                        $files[] = array(
                            'url'   => substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime' => filemtime($path2)
                        );
                    }
                }
            }
        }

        return $files;
    }
}


