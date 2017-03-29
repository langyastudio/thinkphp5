/**
 * 与项目有关的js处理函数
 */
;(function (window, undefined) {
    if (typeof (Hacfin) == "undefined") {
        Hacfin = {};
        window.Hacfin = Hacfin;
    }

    if (typeof (Hacfin.Utility) == "undefined") {
        Hacfin.Utility = {};
    }

    Hacfin.Utility = {
        GetDataFromAPI: function (options) {
            //定义Ajax调用的对象
            var obj = {
                url: "",
                type: "GET",
                dataType: "jsonp",
                jsonp: "jsonp_callback",
                data: {},
                async: true,
                beforeSend: null,
                success: function (res) {
                    switch (res.code) {
                        case 200:
                            if (!$.trim(res)) {
                                return;
                            }
                            if (typeof (res["count"]) != "undefined") {
                                options.callback(res.result, res["count"]);
                            } else if (typeof (res["allcount"]) != "undefined") {
                                options.callback(res.result, res["count"], res["allcount"]);
                            } else {
                                options.callback(res.result);
                            }
                            break;
                        case 442:
                        case 403:
                            var reURL = document.location.href.substr(Hacfin.Common.RootPath().length);
                            document.location.href = "/Access/Login?Url=" + escape(reURL);
                            break;
                        case 524://系统检测到有攻击行为存在
                            Hacfin.Common.ShowError(res.msg);
                            break;
                        default :
                            if (typeof (options["errorback"]) != "undefined") {
                                options.errorback(res.msg, res.code);
                            } else {
                                Hacfin.Common.ShowError(res.msg);
                            }
                            break;
                    }
                },
                error: function (er) {
                    // dump(er)
                    if (typeof (options["errorback"]) != "undefined") {
                        options.errorback(er);
                    } else {
                        Hacfin.Common.ShowError(er);
                    }
                },
                complete: function () {
                    isSyn = false;
                }
            };

            //dataType 数据类型，jsonp跨域/json同源
            if(options['dataType'] != 'undefined'){
                obj.dataType = options['dataType'];
            }

            //post请求
            if (options['type'] != 'undefined') {
                obj.type = options['type'];
            }

            //设置Url
            obj.url = options["url"];
            if (obj.url.indexOf("/") < 0) {
                obj.url = Hacfin.Config.API[obj.url];
            }

            //设置参数值
            obj.data = options["data"] ? options["data"] : {};
            obj.beforeSend = options.beforeSend ? options.beforeSend : null;
            obj.async = (options["async"] != 'undefined') ? options["async"] : true;

            //添加访问的token
            // if (options["token"] || typeof (options["token"]) == "undefined") {
            //     var hacfin_token = Hacfin.Common.GetCookie("MazeCloud");
            //     if (hacfin_token) {
            //         obj.url += "?ACCESS-TOKEN=" + hacfin_token;
            //         obj.data["ACCESS-TOKEN"] = hacfin_token;
            //     }
            //     if (obj.data.length > 0) {
            //         if (typeof (obj.data[0]) == "object" & hacfin_token) {
            //             obj.data.push({"name": "ACCESS-TOKEN", "value": hacfin_token});
            //         }
            //     }
            // }

            //跨域调用
            $.ajax(obj);
        }
    };
})(window);
