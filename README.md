#一、技术构成
- Web框架：ThinkPHP 5.0    
- 模版引擎：artTemplate(js)
- UI：BootStrap
- JS：jQuery
- 字体库：Font-Awesome
- 模块加载：requirejs 
- CSS预编译器：SASS
- 标准化样式：Normalize.css
- 多媒体：JWPlayer + jplayer(音频)
- 构建工具：Gulp
- 包管理器：bower

> 本产品采用 thinkphp 5.0 框架，主要使用了里面的 route、control、view、template 等技术。
> css采用预编译语言sass编写，由 "public/css/sass" 目录自动生成到 "public/css/css"目录。
> **不可新增其他框架或同类插件，只可新增其他插件!**

插件
---
- IE8-兼容性：html5shiv + respond
- 工具-汉字转拼音：jquery.Hz2Py
- 浏览器-检测：bowser
- 浏览器-hash：jquery.hash
- 浏览器-cookie：jquery.cookie
- 浏览器-剪切/复制：clipboard
- DOM-特效：jquery.transit
- DOM-通知：jquery.toastr + layer
- DOM-分页：laypage
- DOM-加载进度：nprogress
- DOM-滚动：iscroll（APP） + waypoints + jquery.scrollTo + jquery.scrollup
- DOM-省略号：jquery.dotdotdot
- DOM-目录树：jquery.jstree
- 图像-轮播：jquery.flexslider
- 图像-处理：cropper
- 图像-显示：viewer
- 图像-居中：jquery.imgliquid
- 图像-灯箱：jquery.Magnific-popup
- 图像-图表：Echarts
- 图像-延迟加载：jquery.lazyload
- 表单-验证：jquery-validation
- 表单-美化：jquery.select2 + placeholder
- 表单-上传：webuploader
- 表单-表情：memoji
- 表单-日期：Bootstrap-datepicker + jquery.calendar

#二、编码命名规范
1. 英文命名，不可使用汉语拼音
2. 所有的文件夹名，采用 **小写**
3. 所有的文件名，采用 **小写**(以 .php 为后缀的除外)
4. URL请求地址 一律 **小写**
6. - app/home/controller --> 控制类
   - app/home/view --> 视图类
   - public/css/sass --> css
   - public/scripts --> js脚本   
   **按照控制器，形成严格的一一对应关系，文件不可乱放**
7. thinkphp、public/assets为第三方库，不可随意更改，保留源码本身的规范。
> **如果有修改，需要在该文件中详细说明！**

#三、修改说明
### public/assets/jwplayer
### public/assets/krpano
### public/assets/layer
### public/assets/laypage