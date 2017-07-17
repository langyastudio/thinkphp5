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
			// if(typeof ajaxNum == 'number')
			//     //为判断页面所有的ajax 请求完毕做准备 每一个请求进行加1
			//     ajaxNum++;

			//定义Ajax调用的对象
			var obj = {
				url: "",
				type: "GET",
				dataType: "jsonp",
				jsonp: "jsonp_callback",
				data: {},
				async: true,
				beforeSend: null,
				success: success,
				error: error,
				complete: complete
			};

			//url
			obj.url = options["url"];
			if (obj.url.indexOf("/") < 0) {
				obj.url = Hacfin.Config.API[obj.url];
			}

			//type，如post请求
			if (options['type'] != undefined) {
				obj.type = options['type'];
			}

			//data
			obj.data = options["data"] ? options["data"] : {};

			//async
			obj.async = (options["async"] != undefined) ? options["async"] : true;

			//beforeSend
			obj.beforeSend = options.beforeSend ? options.beforeSend : null;

			//添加访问的token
			// if (options["token"] == undefined) {
			// 	var hacfin_token = Hacfin.Common.GetCookie("MazeCloud");
			// 	if (hacfin_token) {
			// 		if(obj.type == 'GET'){
			// 			obj.url += "?ACCESS-TOKEN=" + hacfin_token;
			// 		}else{
			// 			obj.data["ACCESS-TOKEN"] = hacfin_token;
			// 		}
			// 	}
			// }

			//跨域调用【解释说明】
			//1、【jsonp】script，link, img 等等标签引入外部资源，都是 GET 请求的，而jsonp就是利用了<script>标签可以链接到不同源的js脚本，来到达跨域目的。
			//   同时决定了 jsonp 一定是 GET 的，它的优势是几乎所有浏览器都支持。
			//2、【cors】跨域资源共享。cors有些老浏览器不支持（IE8以上），但是get/post都支持。http://caniuse.com/#search=cors
			//   IE8/9采用 XDomainRequest且当使用POST请求时，content_type需要设置为text/plain，需要后台提供处理text/plain类型的请求
			//   需要API服务器设定： Access-Control-Allow-Headers、Access-Control-Allow-Methods、Access-Control-Allow-Origin参数
			if ((obj.type == 'POST') && (obj.dataType == 'jsonp')) {
				obj.dataType    = 'json'; //不使用jsonp，使用cors
				if(Hacfin.Common.IsIE8() || Hacfin.Common.IsIE9())
					obj.contentType = 'text/plain';
			}
			$.ajax(obj);

			//回调成功
			function success(res) {
				switch (res.code) {
					case 200:
					case 1000:

						if(typeof isrefresh=='number'&&!isrefresh){
							NProgress.inc(0.2)
						}
						if (!$.trim(res)) {
							return;
						}
						if (typeof (res["count"]) != 'undefined') {
							options.callback(res.result, res["count"]);
						} else if (typeof (res["allcount"]) != 'undefined') {
							options.callback(res.result, res["count"], res["allcount"]);
						} else {
							options.callback(res.result);
						}
						break;
					case 442:
					case 403:
						var reURL = document.location.href.substr(Hacfin.Common.RootPath().length);
						break;
					case 524://系统检测到有攻击行为存在
						Hacfin.Dialog.ShowError(res.msg);
						break;
					case 706:
						var reURL = document.location.href.substr(Hacfin.Common.RootPath().length);
						if(reURL != "/access/empower")
							document.location.href = "/access/empower";
						break;
					case 711:
						Hacfin.Dialog.ShowError(res.msg);
						var reURL = document.location.href.substr(Hacfin.Common.RootPath().length);
						if(reURL != "/access/active")
							document.location.href = "/access/active";
						break;
					case 763:
					case 681:
					case 682:
					case 596:
					case 501:
						var reURL = document.location.href.substr(Hacfin.Common.RootPath().length);
						// if(reURL != "/index/noexsit")
						//    document.location.href = "/index/noexsit";
						if(reURL.search(/detail/gi)!=-1){
							document.location.href = "/index/noexsit";
						}else if(reURL.search(/myupload/gi)!=-1){

							Hacfin.Dialog.ShowError('文件不存在');
						}else if(reURL.search(/course/gi)!=-1){

							Hacfin.Dialog.ShowError('课程不存在');
						}else if(reURL.search(/live/gi)!=-1){
							Hacfin.Dialog.ShowError('直播不存在');
						}
						break;
					default :
						if (typeof (options["errorback"]) != 'undefined') {
							options.errorback(res.msg, res.code,res.result);
						} else {
							Hacfin.Dialog.ShowError(res.msg);
						}
						break;
				}
			}

			//回调失败
			function error(er) {
				if (typeof (options["errorback"]) != 'undefined') {
					options.errorback(er);
				} else {
					Hacfin.Dialog.ShowError(er);
				}
			}

			//回调完成
			function complete() {
				// if(Hacfin.Common.IsPC() && typeof ajaxNum == 'number'){
				//     //判断页面所有的ajax 请求完毕
				//     ajaxcomplete++;
				//     if(ajaxNum == ajaxcomplete && isrefresh == 0){
				//         NProgress.done(true);
				//         isrefresh = 1;
				//     }
				// }

				if(options.complete){
					options.complete();
				}
				isSyn = false;
			}
		}

	};
})(window);
