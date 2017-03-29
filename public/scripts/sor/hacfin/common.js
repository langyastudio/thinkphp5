/**
 * 通用js处理函数，与项目无关
 */
var isSyn = false;
(function (window, undefined) {
    if (typeof (Hacfin) == "undefined") {
        Hacfin = {};
        window.Hacfin = Hacfin;
    }

    if (typeof (Hacfin.Common) == "undefined") {
        Hacfin.Common = {};
    }

    Hacfin.Common = {
        //region Browse
        RootPath: function () {
            var strFullPath = window.document.location.href;
            var strPath = window.document.location.pathname;
            var pos = strFullPath.indexOf(strPath);
            var prePath = strFullPath.substring(0, pos);

            return prePath;
        },

        GetUrlParam: function (name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) {
                return (r[2]);
            }

            return null;
        },

        LocationPre: function () {
            var rebackUrl = Hacfin.Common.GetUrlParam("Url");
            if (rebackUrl) {
                parent.location.href = rebackUrl;
            } else {
                parent.location.href = "/";
            }
        },

        BrowserName: function () {
            var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
            var isOpera = userAgent.indexOf("Opera") > -1; //判断是否Opera
            var isMaxthon = userAgent.indexOf("Maxthon") > -1; //判断是否傲游3.0
            var isIE = userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && !isOpera; //判断是否IE
            var isFF = userAgent.indexOf("Firefox") > -1; //判断是否Firefox
            var isSafari = userAgent.indexOf("Safari") > -1 && userAgent.indexOf("Chrome") < 1; //判断是否Safari
            var isChrome = userAgent.indexOf("Chrome") > -1; //判断是否Chrome

            if (isIE) {
                var IE5 = IE55 = IE6 = IE7 = IE8 = false;
                var reIE = new RegExp("MSIE (+);");
                reIE.test(userAgent);
                var fIEVersion = parseFloat(RegExp["$1"]);

                IE55 = fIEVersion == 5.5;
                IE6 = fIEVersion == 6.0;
                IE7 = fIEVersion == 7.0;
                IE8 = fIEVersion == 8.0;

                if (IE55) {
                    return "IE55";
                }
                if (IE6) {
                    return "IE6";
                }
                if (IE7) {
                    return "IE7";
                }
                if (IE8) {
                    return "IE8";
                }
            }

            if (isFF) {
                return "FF";
            }
            if (isOpera) {
                return "Opera";
            }
            if (isMaxthon) {
                return "Maxthon";
            }
            if (isSafari) {
                return "Safari";
            }
            if (isChrome) {
                return "Chrome";
            }
        },

        IsIE8: function () {
            return navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.split(";")[1].replace(/[ ]/g, "") == "MSIE8.0";
        },

        IsIE9: function () {
            return navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.split(";")[1].replace(/[ ]/g, "") == "MSIE9.0";
        },

        /**
         * 判断客户端类型是否是PC端
         * @return bool PC,true;不是PC,false
         */
        IsPC: function () {
            var userAgentInfo = navigator.userAgent.toLowerCase();
            //console.log(userAgentInfo);
            var Agents = ["android", "symbianos", "windows phone",
                "ipad", "ipod", "iphone", "android", "phone", "mobile",
                "wap", "netfront", "java", "opera mobi", "opera mini", "ucweb",
                "windows ce", "symbian", "series", "webos", "sony",
                "blackberry", "dopod", "nokia", "samsung", "palmsource", "xda",
                "pieplus", "meizu", "midp", "cldc", "motorola", "foma",
                "docomo", "up.browser", "up.link", "blazer", "helio", "hosin",
                "huawei", "novarra", "coolpad", "webos", "techfaith",
                "palmsource", "alcatel", "amoi", "ktouch", "nexian",
                "ericsson", "philips", "sagem", "wellcom", "bunjalloo", "maui",
                "smartphone", "iemobile", "spice", "bird", "zte-", "longcos",
                "pantech", "gionee", "portalmmm", "jig browser", "hiptop",
                "benq", "haier", "^lct", "320x320", "240x320", "176x220",
                "w3c ", "acs-", "alav", "alca", "amoi", "audi", "avan", "benq",
                "bird", "blac", "blaz", "brew", "cell", "cldc", "cmd-", "dang",
                "doco", "eric", "hipt", "inno", "ipaq", "java", "jigs", "kddi",
                "keji", "leno", "lg-c", "lg-d", "lg-g", "lge-", "maui", "maxo",
                "midp", "mits", "mmef", "mobi", "mot-", "moto", "mwbp", "nec-",
                "newt", "noki", "oper", "palm", "pana", "pant", "phil", "play",
                "port", "prox", "qwap", "sage", "sams", "sany", "sch-", "sec-",
                "send", "seri", "sgh-", "shar", "sie-", "siem", "smal", "smar",
                "sony", "sph-", "symb", "t-mo", "teli", "tim-", /*"tosh",*/ "tsm-",
                "upg1", "upsi", "vk-v", "voda", "wap-", "wapa", "wapi", "wapp",
                "wapr", "webc", "winw", "winw", "xda", "xda-", "Googlebot-Mobile"];
            var flag = true;
            for (var v = 0; v < Agents.length; v++) {
                if (userAgentInfo.indexOf(Agents[v]) > 0) {
                    flag = false;
                    break;
                }
            }

            return flag;
        },
        //endregion Browse

        //region Form
        SetDataToForm: function (formid, data) {
            var key, value, tagName, type, arr;

            for (x in data) {
                key = x;
                value = data[x];

                $("#" + formid).find("[name='" + key + "'],[name='" + key + "[]']").each(function () {
                    tagName = $(this)[0].tagName;
                    type = $(this).attr('type');

                    if (tagName == 'INPUT') {
                        if (type == 'radio' || type == 'checkbox') {
                            $(this).checked($(this).val() == value);
                        } else {
                            $(this).val(value);
                        }
                    } else if (tagName == 'SELECT' || tagName == 'TEXTAREA') {
                        $(this).val(value);
                    } else {
                        $(this).text(value);
                    }
                });
            }
        },

        SerializeForm2Json: function (formJObj) {
            var serializeObj = {};
            $(formJObj.serializeArray()).each(function () {
                serializeObj[this.name] = this.value;
            });
            return serializeObj;
        },
        //endregion Form

        //region  assets
        ShowError: function (msg, impt,time) {
            if (impt) {
                return layer.alert(msg, {icon: 5});
            } else {
                if(time){
                    return layer.msg(msg, {icon: 5,time:time});
                }else{
                    return layer.msg(msg, {icon: 5});

                }
            }
        },

        Msg: function (msg, noIcon) {
            if (noIcon) {
                return layer.msg(msg);
            } else {
                return layer.msg(msg, {icon: 7});
            }
        },

        /**
         * 会话气泡 基于jquery.js 和 layer.js
         * @param  msg string 消息
         * @param selecter string 选择器,气泡附着在何处 如"#id",".class"
         * @param where int 气泡方位 1,上;2,右;3,下;4,左
         * @param time int 持续时间(ms)
         * @returns int layer句柄
         */
        Tips: function (msg, selecter, where, time) {
            if (!where) {
                where = 1;
            }
            if (!time) {
                time = 1000;
            } else if (time <= 0) {
                time = 1000 * 3600 * 24;
            }
            return layer.tips(msg, selecter, {tips: [where, "#1DB5FF"], tipsMore: true, time: time});
        },

        /**
         * 操作确认(不阻塞后面的代码执行; 基于layer.js)
         * @param msg string 提示信息
         * @param yFn function 确定后执行的操作
         * @param nFn function 确定后执行的操作,可选
         * @param btn1Name string 第一个按钮的名称,可选
         * @param btn2Name string 第二个按钮的名称,可选
         * @constructor
         */
        Cfm: function (msg, yFn, nFn, btn1Name, btn2Name) {
            var option = {icon: 3, title: '操作提示', shift: 3};
            if (nFn) {
                if (!btn1Name) {
                    btn1Name = "确定";
                }
                if (!btn2Name) {
                    btn2Name = "取消";
                }
                option.btn = [btn1Name, btn2Name];
            }
            layer.confirm(msg, option,
                    function (index) {//确定
                        yFn();
                        layer.close(index);
                    },
                    function (index) {//取消
                        if (nFn) {
                            nFn();
                        }
                        layer.close(index);
                    }
            );
        },

        Loading: function (isAlwayShow) {
            if(isAlwayShow){
                return layer.load(0, {
                    shade: [0.1, '#000'],
                });
            }else{
                return layer.load(0, {
                    time: 10 * 1000,
                    shade: [0.1, '#000'],
                });
            }

        },

        LoadingClose: function (layid) {
            layer.close(layid);
        },

        ShowSuccess: function (msg, alwaysShow,fn) {
            if (alwaysShow) {
                layer.alert(msg, {icon: 6},function(){
                    if(fn){
                        fn()
                    }

                });
            } else {
                layer.msg(msg, {icon: 6});
            }
        },
        //endregion assets

        /*
         * 根据 id 渲染模板 基于artTemplate.js
         * @param tgt  目标 id
         * @param tpl  根据 id 渲染模板
         * @param data 模版数据列表
         *
         * @link 语法参考 https://github.com/aui/artTemplate/wiki/syntax:simple
         *
         */
        Tpl: function (targetId, tplId, data) {
            if(Hacfin.Common.IsIE8() && $("#"+targetId)[0].tagName == "TBODY"){
                $("#"+targetId).html(template(tplId, data))
            }else {
                document.getElementById(targetId).innerHTML = template(tplId, data);
            }
        },

        /*
         * 根据 id 渲染模板 基于artTemplate.js
         * @param tgt         目标 id
         * @param apply_list  模版内容
         * @param data        模版数据列表
         *
         * @link 语法参考 https://github.com/aui/artTemplate/wiki/syntax:simple
         *
         */
        Tpl_Compile: function (targetId, apply_list, data) {
            var render = template.compile(apply_list);
            document.getElementById(targetId).innerHTML = render(data);
        },
    };
})(window);