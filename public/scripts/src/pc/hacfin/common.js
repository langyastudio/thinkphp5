/**
 * 通用js处理函数，与项目无关
 */
var isSyn = false;
(function (window, undefined) {
	if (typeof (Hacfin) == "undefined") {
		Hacfin        = {};
		window.Hacfin = Hacfin;
	}

	if (typeof (Hacfin.Common) == "undefined") {
		Hacfin.Common = {};
	}

	Hacfin.Common = {
		//==================================================【1】 Time START ==========================================//
		/*
		 在数字前，追加值0
		 */
		PadPre      : function (num, n) {
			var len = num.toString().length;
			while (len < n) {
				num = "0" + num;
				len++;
			}
			return num;
		},
		//Todo:该函数效率可能较低!!
		/**
		 * unix时间戳转日期时间
		 * @param  dateStr unix时间戳  dateStr格式为"1293072805"
		 * @param  type 返回的日期时间格式
		 *         type=0 返回 0000-00-00 00:00:00
		 *         type=1 返回 0000-00-00 00:00
		 *         type=2 返回 0000-00-00
		 *         type=3 返回 00:00
		 *         type为其他值 返回0000-00-00 00:00
		 * @return string
		 */
		Unix2Time   : function (dateStr, type) {
			var dateObj = new Date(parseInt(dateStr) * 1000);
			if (!dateStr) {
				dateObj = new Date();
			}

			if (0 == type) {
				return dateObj.getFullYear() + '-' + Hacfin.Common.PadPre((dateObj.getMonth() + 1), 2) + '-' + Hacfin.Common.PadPre(dateObj.getDate(), 2) + ' '
						+ Hacfin.Common.PadPre(dateObj.getHours(), 2) + ':' + Hacfin.Common.PadPre(dateObj.getMinutes(), 2) + ':' + Hacfin.Common.PadPre(dateObj.getSeconds(), 2);
			}
			else if (1 == type) {
				return dateObj.getFullYear() + '-' + Hacfin.Common.PadPre((dateObj.getMonth() + 1), 2) + '-' + Hacfin.Common.PadPre(dateObj.getDate(), 2) + ' '
						+ Hacfin.Common.PadPre(dateObj.getHours(), 2) + ':' + Hacfin.Common.PadPre(dateObj.getMinutes(), 2);
			} else if (2 == type) {
				return dateObj.getFullYear() + '-' + Hacfin.Common.PadPre((dateObj.getMonth() + 1), 2) + '-' + Hacfin.Common.PadPre(dateObj.getDate(), 2);
			} else if (3 == type) {
				return Hacfin.Common.PadPre(dateObj.getHours(), 2) + ':' + Hacfin.Common.PadPre(dateObj.getMinutes(), 2);
			}
			else {
				return dateObj.Format("yyyy-MM-dd HH:mm")
			}
		},
		Time2Unix   : function (dateStr) {//dateStr格式为"2014-05-08 00:22:11”
			if (typeof dateStr != "string") {
				console.log("Time2Unix()收到的参数不是字符型");
				return;
			}
			var newstr   = dateStr.replace(/-/g, '/');
			var date     = new Date(newstr);
			var time_str = date.getTime().toString();
			return time_str.substr(0, 10);
		},
		RelativeTime: function (secends) {
			if (typeof(secends) == "undefined") {
				return "0秒前"
			}
			if (!$.isNumeric(secends)) {
				return "0秒前"
			} else if (parseInt(secends) < 0) {
				return "0秒前"
			}
			var fz = 60;//分钟
			var xs = fz * 60;//小时
			var ts = xs * 24;//天数
			var ns = ts * 365;//年
			if (secends >= ns) {
				return Math.round(secends / ns) + "年前";
			} else if (secends >= ts) {
				return Math.round(secends / ts) + "天前";
			} else if (secends >= xs) {
				return Math.round(secends / xs) + "小时前";
			} else if (secends >= fz) {
				return Math.round(secends / fz) + "分钟前";
			} else {
				return secends + "秒前";
			}
		},
		Now         : function () {
			return Math.round(new Date().getTime() / 1000);
		},
		//================================================== Time END ==========================================//


		//==================================================【2】 Cookie/Session START ==========================================//
		/**
		 * 获取网站信息
		 * @param key    	siteName/webCfg
		 * @param fn		回调函数
		 * @constructor
		 */
		Cache_GetSiteInfo: function (key, fn) {
			var val = this.GetSessionStorage(key);
			if (!val) {
				Hacfin.Utility.GetDataFromAPI({
					url     : "SiteInfo",
					type    : "Get",
					callback: function (res) {
						Hacfin.Common.SetSessionStorage("siteName", res.websitename + "-");
						Hacfin.Common.SetSessionStorage("webCfg", JSON.stringify(res)); //存储为Json串

						fn(Hacfin.Common.GetSessionStorage(key));
					}
				});
			} else {
				fn(val);
			}
		},
		GetSessionStorage   : function (key) {
			return sessionStorage.getItem(key);
		},
		SetSessionStorage   : function (key, data) {
			sessionStorage.setItem(key, data);
		},
		DeleteSessionStorage: function (key) {
			sessionStorage.removeItem(key);
		},

		GetLocalStorage     : function (key) {
			return localStorage.getItem(key);
		},
		SetLocalStorage     : function (key, data) {
			localStorage.setItem(key, data);
		},
		DeleteLocalStorage  : function (key) {
			localStorage.removeItem(key);
		},

		GetCookie             : function (name) {
			var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
			if (arr = document.cookie.match(reg)) {
				return unescape(arr[2]);
			} else {
				return "";
			}
		},
		Setcookie             : function (name, value, min) {
			if (min) {
				var date = new Date();
				date.setTime(date.getTime() + (min * 60 * 1000));
				var expires = "; expires=" + date.toGMTString();
			}
			else {
				expires = "";
			}
			document.cookie = name + "=" + value + expires + "; path=/";
		},
		DeleteCookie          : function (name) {
			var exp = new Date();
			exp.setTime(exp.getTime() - 16000);
			var cval = Hacfin.Common.GetCookie(name);
			if (cval != null) {
				document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString() + ";path=/";
			}
		},
		//================================================== Cookie/Session END ==========================================//


		//==================================================【3】 表单 START ==========================================//
		SetData2Form      : function (formid, data) {
			var key, value, tagName, type, arr;

			for (x in data) {
				key   = x;
				value = data[x];

				$("#" + formid).find("[name='" + key + "'],[name='" + key + "[]']").each(function () {
					tagName = $(this)[0].tagName;
					type    = $(this).attr('type');

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
		ClearForm         : function (formid) {
			$("#" + formid).find("[name]").each(function () {
				var key, value, tagName, type, arr;

				key     = $(this).attr("name");
				tagName = this.tagName;
				type    = $(this).attr('type');

				if (tagName == 'INPUT') {
					if (type == 'radio' || type == 'checkbox') {
						$(this).checked(false);
					} else {
						$(this).val("");
					}
				} else if (tagName == 'SELECT' || tagName == 'TEXTAREA') {
					$(this).val("");
				}
			})
		},
		GetDataFromForm   : function (formid) {
			var returndata = {};

			$("#" + formid).find("[name]").each(function () {
				var key, value, tagName, type, arr;
				key     = $(this).attr("name");
				tagName = this.tagName;
				type    = $(this).attr('type');

				if (tagName == 'INPUT') {
					if (type == 'radio' || type == 'checkbox') {
						if ($(this).is(':checked')) {
							returndata[key] = $(this).val();
						}
					} else {
						returndata[key] = $(this).val();
					}
				} else if (tagName == 'SELECT' || tagName == 'TEXTAREA') {
					returndata[key] = $(this).val();
				}
			});

			return returndata;
		},
		SerializeForm2Json: function (formJObj) {
			var serializeObj = {};
			$(formJObj.serializeArray()).each(function () {
				serializeObj[this.name] = this.value;
			});
			return serializeObj;
		},
		Set_Focus         : function (obj) {
			var sel   = window.getSelection();
			var range = document.createRange();
			range.selectNodeContents(obj[0]);
			range.collapse(false);
			sel.removeAllRanges();
			sel.addRange(range);
		},
		//================================================== 表单 END ==========================================//


		//==================================================【4】 文件 START ==========================================//
		/**
		 * 依赖spark.md5/spark-md5.js
		 *
		 * 自定义算法 计算MD5
		 *
		 * 1、文件等于小于2M时 计算整个文件文件的md5值
		 * 2、文件大于2M时，使用 "文件头1M + 文件尾1M + 文件长度"计算MD5值
		 *
		 * @param file webuploader的文件对象
		 * @param fn   回调函数 fn(md5)
		 *
		 *  浏览器支持：>= IE10
		 *
		 *  Todo：IE8-9的支持
		 */
		FileMd5      : function (file, fn) {

			//判断是否支持File对象
			if (typeof File == "undefined") {
				return;
			}

			var blobSlice = File.prototype.slice || File.prototype.mozSlice || File.prototype.webkitSlice,
				chunkSize = 1024 * 1024,
				chunks    = 0;
			currentChunk  = 0;
			blob          = (file instanceof Blob) ? file : file.source.getSource();
			spark         = new SparkMD5();
			fileReader    = new FileReader();

			//异步函数
			fileReader.onload  = function (e) {
				// append array buffer
				if (e && e.target.result)
					spark.appendBinary(e.target.result);
				else if (fileReader.content)//extend FileReader >=IE10 依赖FileReader.prototype.readAsBinaryString
					spark.appendBinary(fileReader.content);
				else {
					//Error
				}

				currentChunk++;
				if (currentChunk < chunks) {
					loadNext();
				}
				else {
					loadEnd();
				}
			};
			fileReader.onerror = function () {

			};

			if (file.size > 2 * chunkSize) {//>2M的文件
				chunks = 3;
			}
			else {
				chunks = 1;
			}

			function loadNext() {
				if (3 == chunks) {
					var fstart = 0,
						fend   = chunkSize,
						lstart = file.size - chunkSize,
						lend   = file.size;

					if (0 == currentChunk)
						fileReader.readAsBinaryString(blobSlice.call(blob, fstart, fend));
					else if (1 == currentChunk)
						fileReader.readAsBinaryString(blobSlice.call(blob, lstart, lend));
					else if (2 == currentChunk) {
						//此时直接追加文件大小的文本 结束md5的计算
						spark.appendBinary(file.size.toString());
						loadEnd();
					}
				} else {
					fileReader.readAsBinaryString(blobSlice.call(blob, 0, file.size));
				}
			}

			function loadEnd() {
				var fileMd5 = spark.end();
				if (typeof fn != 'undefined') {
					fn(fileMd5);
				}
			}

			loadNext();
		},
		FileSize     : function (fileSize) {
			if (typeof(fileSize) == "undefined") {
				return "-"
			}
			if (!$.isNumeric(fileSize)) {
				return "-"
			}
			var kb = 1024;
			var mb = kb * 1024;
			var gb = mb * 1024;
			var tb = gb * 1024;
			if (fileSize >= tb) {
				return parseFloat((fileSize / tb).toFixed(2)) + "T"
			} else if (fileSize >= gb) {
				return parseFloat((fileSize / gb).toFixed(2)) + "G"
			} else if (fileSize >= mb) {
				return parseFloat((fileSize / mb).toFixed(2)) + "M"
			} else if (fileSize >= kb) {
				return parseFloat((fileSize / kb).toFixed(2)) + "KB"
			} else {
				return fileSize + "B"
			}
		},
		ValidFileName: function (FileName) {
			var result     = {
				success: true,
				msg    : ""
			};
			var rename_REG = /[\\\/\:\*\?"<>\|]/i;
			if (rename_REG.test(FileName)) {
				result.success = false;
				result.msg     = "文件名不能包含以下字符：<,>,|,*,?,,/!";
			}
			var charLength = this.GetCharLength(FileName);
			if (charLength == 0) {
				result.success = false;
				result.msg     = "文件(夹)名称不能为空，请输入文件名称";
			}
			else if (charLength > 255) {
				result.success = false;
				result.msg     = "文件(夹)名称长度不能超过255字节";
			}
			return result;
		},
		//================================================== 文件 END ==========================================//


		//==================================================【5】 浏览器/URL START ==========================================//
		RootPath   : function () {
			var strFullPath = window.document.location.href;
			var strPath     = window.document.location.pathname;
			var pos         = strFullPath.indexOf(strPath);
			var prePath     = strFullPath.substring(0, pos);
			return prePath;
		},
		LocationPre: function () {
            var registerUrl =  location.protocol + "//" + location.host
                + "/access/register";
            var forgetUrl = location.protocol + "//" + location.host
                + "/access/forget?step=3";
			if (bowser.msie) {
				if (window.history.length > 0) {
                    if(document.referrer == registerUrl || document.referrer == forgetUrl){
                        location.href = '/index/index';
                    }else{
                        window.history.go(-1);
                    }
                }else {
					parent.location.href = '/';
				}
			}
			else {
				if (window.history.length > 2) {
					if(document.referrer == registerUrl || document.referrer == forgetUrl){
                     	// location.href = '/?temp='+Math.random()+'';
                        location.href = '/index/index';
					}else{
                        window.history.go(-1);
					}
				}
				else {
					parent.location.href = '/';
				}
			}
		},
		IsIE8      : function () {
			return navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.split(";")[1].replace(/[ ]/g, "") == "MSIE8.0";
		},

		IsIE9: function () {
			return navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.split(";")[1].replace(/[ ]/g, "") == "MSIE9.0";
		},
		/**
		 * 判断客户端类型是否是PC端
		 * @return bool PC,true;不是PC,false
		 */
		IsPC : function () {
			var userAgentInfo = navigator.userAgent.toLowerCase();
			//console.log(userAgentInfo);
			var Agents        = ["android", "symbianos", "windows phone",
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
			var flag          = true;
			for (var v = 0; v < Agents.length; v++) {
				if (userAgentInfo.indexOf(Agents[v]) > 0) {
					flag = false;
					break;
				}
			}
			return flag;
		},
		//================================================== 浏览器 END ==========================================//


		//==================================================【6】 事件 START ==========================================//
		TouchEvent     : function (obj) {
			var start_x;
			var start_y;
			var end_x;
			var end_y;
			obj.on("touchstart", function (event) {
				if (event.originalEvent.targetTouches.length == 1) {
					var touch = event.originalEvent.targetTouches[0];
					start_x   = touch.pageX;
					start_y   = touch.pageY;
				}
				;
			});
			obj.on("touchmove", function (event) {
				// event.preventDefault();
				if (event.originalEvent.targetTouches.length == 1) {
					var touch = event.originalEvent.targetTouches[0];
					move_x    = touch.pageX;
					move_y    = touch.pageY;
				}
				;
			});
			obj.on("touchend", function (event) {
				if (event.originalEvent.changedTouches.length == 1) {
					var touch = event.originalEvent.changedTouches[0];
					end_x     = touch.pageX;
					end_y     = touch.pageY;
				}
				;
				var numRange = 100;
				if (start_x - end_x > numRange) {

				} else if (start_x - end_x < -numRange) {
					$("#catalogC").stop().animate({left: $(window).width()}, 800)
					$(".m-filter").find('i').attr("class", "fa fa-caret-up");
					$("#catalogwrap").hide();
				}
			});
		},
		FilterClick    : function () {
			$(".m-filter").on("click", function () {
				if ($(this).find("i").hasClass('fa-caret-up')) {
					$(this).find("i").attr("class", "fa fa-caret-down");
					$("#catalogC").show();
				} else {
					$(this).find("i").attr("class", "fa fa-caret-up");
					$("#catalogC").hide();
				}


				// $("#catalogC").stop().animate({left:'40px'},800)

				// $("#catalogwrap").show();
				// $("#catalogwrap").css('height', $("#catalogC").outerHeight(true))

			});
			// $("#catalogwrap").on("click",function(){
			//     $("#catalogC").stop().animate({left:$(window).width()},800);
			//     $(".m-filter").find('i').attr("class", "fa fa-caret-up");
			//     $("#catalogwrap").hide();
			//
			//
			// })
		},
		//获取事件元素
		EventTarget    : function (evt) {
			evt     = evt || window.event;
			var ele = null;
			if (evt.srcElement) {
				ele = evt.srcElement;
			}
			else {
				ele = evt.target;
			}

			return ele;
		},
		/*
		 * 文本复制到剪贴板
		 * @param tcopyfun  待复制文本的函数
		 * @param id        事件对象id号或class名称
		 *
		 * @link 插件参考：https://clipboardjs.com
		 *
		 */
		ClipCopy       : function (tcopyfun, id, bIsId) {
			var isIE8 = Hacfin.Common.IsIE8();
			if (isIE8) {
				window.clipboardData.setData("Text", tcopyfun());//IE8的复制到剪切板
			} else {
				var target    = bIsId ? ('#' + id) : ('.' + id);
				var clipboard = new Clipboard(
						target,
						{
							text: function (trigger) {
								return tcopyfun();
							}
						});

				clipboard.on('success', function (e) {

				});

				clipboard.on('error', function (e) {
				});

				return clipboard;
				//clipboard.destroy();
			}
		},
		ClipDestory    : function (clipboard) {
			if (clipboard) {
				clipboard.destroy();
			}

		},
		//     var options = {
		//         copyfun: function () {
		//             return $(".urltxt").val() + ' ' + $(".pwdtxt").val();
		//         },
		//         id: "btn"
		//     };
		//     $("#btn").bind("click", options, clipCopy);
		/*
		 * 绑定文本复制到剪贴板的事件处理函数
		 * @param options 事件传递的参数列表
		 *              copyfun     ： 返回需要复制到剪贴板的文本
		 *              id          ： 触发单击事件的DOM的Id号
		 *
		 * @link 插件参考：https://clipboardjs.com
		 *
		 */
		EventClipCopy  : function (e) {
			var options  = e.data;
			var tcopyfun = options.copyfun;
			var id       = options.id ? options.id : "btn";

			return Hacfin.Common.ClipCopy(tcopyfun, id, true);
		},
		/**由于Chrome流览器对中文输入的时候拼音也会在输入框中显示导致Maxlength函数截取错误的问题
		 * 限制文本框的字数,并给出提示 基于jquery.js 和 layer.js bowser.js
		 * @param obj 文本框对象  如: $("#myText")
		 * @param maxNum 最大字数  如: 20
		 * @param notInput Boolean值 为true时表示不是input,textarea等输入框, 而是div等元素
		 */
		EventBindMaxLen: function (obj, num, notInput) {
			if (Hacfin.Common.IsIE8()) {
				obj.on('paste', function () {
					setTimeout(function () {
						obj.find("img").remove()
					}, 0)
				})
			}
			var cplock = false;
			var event  = 'keyup';
			if (!Hacfin.Common.IsPC()) {
				event = 'input'
			}
			obj.on(event, function () {
				if (!cplock) {
					Hacfin.Common.MaxLength(obj, num, notInput)
				}
			}).on('compositionstart', function () {
				cplock = true;
			}).on('compositionend', function () {
				cplock = false;
				if (bowser.chrome) {
					Hacfin.Common.MaxLength(obj, num, notInput)
				}
			})
			obj.on('paste', function () {
                setTimeout(function () {
                    Hacfin.Common.MaxLength(obj, num, notInput)
                }, 0)

			})
		},
		/**
		 * 限制文本框的字数,并给出提示 基于jquery.js 和 layer.js
		 * @param obj 文本框对象  如: $("#myText")
		 * @param maxNum 最大字数  如: 20
		 * @param notInput Boolean值 为true时表示不是input,textarea等输入框, 而是div等元素
		 */
		MaxLength      : function (obj, maxNum, notInput) {

			var inputText, length, $imgCount = 0;
			if (notInput) {
				inputText = obj.html();
				var $img  = obj.find("img");
				$imgCount = $img.length;
			} else {
				inputText = obj.val();
			}

			length = inputText.length + $imgCount;
			if (length > maxNum) {
				//删除多余内容
				var divContent, lastChar;
				for (var i = length; i > maxNum; i--) {
					divContent = obj.html();
					lastChar   = divContent.replace(/^(.*[n])*.*(.|n)$/g, "$2");
					if (lastChar == ">") { //img
						obj.children(":last-child").remove();
					} else { //文本
						divContent = divContent.substring(0, divContent.length - 1);
						obj.html(divContent);
						if (!this.IsIE8()) {
							this.Set_Focus(obj);
						}

					}
				}
				if (notInput) {
					if ($imgCount == 0) {
						obj.html(inputText.substring(0, maxNum));
						if (!this.IsIE8()) {
							this.Set_Focus(obj);
						}

					}
				} else {
					obj.val(inputText.substring(0, maxNum));
				}
				length = maxNum;
				Hacfin.Dialog.ShowError("最多输入" + maxNum + "个字符");
				if (this.IsIE8()) {
					//解决IE8 下 光标浮在提示框的上面
					obj.blur()
				}
			}
			if (length / maxNum > 0.5) {
				var where = 2;
				if (!Hacfin.Common.IsPC()) {
					where = 3;

				}

				layer.tips(length + "/" + maxNum, obj, {tips: [where, "#0A8ACB"], tipsMore: true, time: 1000});
			}
		},
		/**
		 * 动态加载数据  基于jQuery.js
		 * @param jObj jquery对象 要动态加载数据的对象 没有滚动条的对象无效
		 * @param fn 加载完后执行的函数 可选
		 * @param isInsert 是否是滚动条到顶部是加载 可选,默认滚动条到底时执行
		 */
		LoadMore       : function (jObj, fn, isInsert) {
			var nScrollHight;//需要滚动的整个区域
			var nScrollTop;//可见区域顶部距整个区域顶部的距离
			var nDivHight = jObj.height();

			jObj.scroll(function () {

				nScrollHight = $(this)[0].scrollHeight;
				nScrollTop   = $(this)[0].scrollTop;

				if (isInsert === true) {//滚动条到顶部时追加内容
					if (nScrollTop <= 0) {
						if (typeof fn == 'function') {
							fn();
						}
					}
				} else {//滚动条到底部时追加内容
					if (nScrollTop + nDivHight >= nScrollHight) {
						if (typeof fn == 'function') {
							fn();
						}
					}
				}
			});
		},
		//================================================== 事件 END ==========================================//


		ImgResize    : function (obj, options) {
			obj.imgLiquid(options)
		},
		WebTitle     : function (pageName) {
			Hacfin.Common.Cache_GetSiteInfo("siteName", function (val) {
				document.title = val + "-" + pageName;
			});
		},
		GetCharLength: function (str) {
			var realLength = 0;
			for (var i = 0; i < str.length; i++) {
				charCode = str.charCodeAt(i);
				if (charCode >= 0 && charCode <= 128) {
					realLength += 1;
				} else {
					realLength += 2;
				}
			}
			return realLength;
		},
		/*
		 * 根据 id 渲染模板 基于artTemplate.js
		 * @param tgt  目标 id
		 * @param tpl  根据 id 渲染模板
		 * @param data 模版数据列表
		 *
		 * @link 语法参考 https://github.com/aui/artTemplate/wiki/syntax:simple
		 *
		 */
		Tpl          : function (targetId, tplId, data) {
			if (Hacfin.Common.IsIE8() && $("#" + targetId)[0].tagName == "TBODY") {
				$("#" + targetId).html(template(tplId, data))
			} else {
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
		Tpl_Compile  : function (targetId, apply_list, data) {
			var render                                  = template.compile(apply_list);
			document.getElementById(targetId).innerHTML = render(data);
		},
		/*
		 * 文件上传 基于百度上传控件 webuploader.js
		 *
		 * @options
		 *      //为不包含的选项
		 *      innerOptions  : {
		 *      }
		 *
		 *      // 【必传】选择文件的按钮
		 *      pick: {
		 *          label: '上传',    //默认为 “上传”
		 *          id: '#' + id,    //【必传】上传按钮的Id号
		 *          multiple: false, //是否支持多选 默认false
		 *          }
		 *
		 *      accepttype    : //可接受的文件格式 1 Excel; 2 图片
		 *
		 *      server        : 【必传】执行的上传的API地址,必传参数
		 *      deleteServer  : 执行的删除的API地址,默认为undefined
		 *      fileNumLimit        : undefined,//验证文件总数量, 超出则不允许加入队列
		 *      fileSizeLimit       : undefined,//验证文件总大小是否超出限制, 超出则不允许加入队列。
		 *      fileSingleSizeLimit : undefined,//验证单个文件大小是否超出限制, 超出则不允许加入队列
		 *      PostbackHold        : false,
		 *      auto                : 自动上传，默认为true
		 *      deletedefaultcss    : false
		 *
		 *      fileQueued    : function (file),默认为undefined
		 *      fileDequeued  : function (file),默认为undefined
		 *      uploadProgress: function( file, percentage ),默认为undefined
		 *      uploadSuccess : 【必传】function (file, obj)
		 *      uploadComplete: function( file ),默认为undefined
		 *      error         : function (file)
		 *
		 * @link 参考 http://fex.baidu.com/webuploader/
		 *
		 */
		WebUploader  : function (options) {

			if (!options || !options.server) {
				return null;
			}

			//1.0 检测WebUploader是否合法
			if ((typeof WebUploader) == "undefined") {
				var casspath = "/public/assets/webuploader/webuploader.css";
				var jspath   = "/public/assets/webuploader/webuploader.js";

				$("<link>").attr({rel: "stylesheet", type: "text/css", href: casspath}).appendTo("head");
				$.getScript(jspath) //该函数异步执行，所以要小心!!需要开启ajax cache机制
						.done(function () {
							rtn = WebUploaderInit();
						})
						.fail(function () {
							Hacfin.Dialog.ShowError("请检查webuploader的路径是否正确!");
						});
			}
			else {
				return WebUploaderInit();
			}

			function WebUploaderInit() {

				if (!WebUploader.Uploader.support()) {
					var error = "上传控件不支持您的浏览器！请尝试升级flash版本或者使用Chrome引擎的浏览器。<a target='_blank' href='http://ie.sogou.com'>下载页面</a>";
					Hacfin.Dialog.ShowError(error);
					return null;
				}

				//2.0 配置上传参数
				//2.1 创建默认参数
				var defaults = {
					auto               : true,
					innerOptions       : {},
					server             : undefined,
					deleteServer       : undefined,
					fileNumLimit       : undefined,//验证文件总数量, 超出则不允许加入队列
					fileSizeLimit      : undefined,//验证文件总大小是否超出限制, 超出则不允许加入队列。
					fileSingleSizeLimit: undefined,//验证单个文件大小是否超出限制, 超出则不允许加入队列
					PostbackHold       : false,
					// 选择文件的按钮
					pick               : {
						label   : '上传',
						id      : undefined,
						multiple: false
					}
				};
				if (options.accepttype != undefined) {
					if (options.accepttype == 1) {//Excel
						options.accept = {
							title     : 'Excels',
							extensions: 'xls'
						};
					}
					else if (options.accepttype == 2) {//图片
						options.accept = {
							title     : 'Images',
							extensions: 'gif,jpg,jpeg,bmp,png,ico',
							mimeTypes : 'image/jpeg,image/png,image/gif,image/bmp,image/x-icon'

						};
					} else if (options.accepttype == 3) {//图片
						options.accept = {
							title     : 'Images',
							extensions: 'gif,jpg,jpeg,png',
							mimeTypes : 'image/jpeg,image/png,image/gif'

						};
					}

				}

				//2.2 合并参数
				var opts               = $.extend(defaults, options);
				var webuploaderoptions = $.extend({
							swf                : '/public/assets/webuploader/Uploader.swf',// swf文件路径
							server             : opts.server,// 文件接收服务端
							deleteServer       : opts.deleteServer,
							PostbackHold       : opts.PostbackHold,
							pick               : opts.pick,// 选择文件的按钮
							auto               : opts.auto,
							accept             : opts.accept,
							fileNumLimit       : opts.fileNumLimit,
							fileSizeLimit      : opts.fileSizeLimit,
							fileSingleSizeLimit: opts.fileSingleSizeLimit,
							resize             : false,  //不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
							disableGlobalDnd   : true,    // 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开
							compress           : false,//图片在上传前不进行压缩
							duplicate          : true,
						},
						opts.innerOptions);

				//3.0 创建
				var uploader = WebUploader.create(webuploaderoptions);

				//Todo: 删除id 下的第一个div中的webuploader-pick样式
				if (opts.deletedefaultcss) {
					$(opts.pick.id + ' div:first').removeClass('webuploader-pick');
				}

				//4.0 执行相关事件
				if (options.fileQueued) {
					uploader.on('fileQueued', options.fileQueued);
				}

				if (options.fileDequeued) {
					uploader.on('fileDequeued', options.fileDequeued);
				}

				if (options.startUpload) {
					uploader.on('startUpload', options.startUpload);
				}

				if (options.uploadProgress) {
					uploader.on('uploadProgress', options.uploadProgress);
				}

				if (options.uploadSuccess) {
					uploader.on('uploadSuccess', options.uploadSuccess);
				}

				if (options.uploadComplete) {
					uploader.on('uploadComplete', options.uploadComplete);
				}

				if (options.uploadStart) {
					uploader.on('uploadStart', options.uploadStart);
				}

				if (options.filesQueued) {
					uploader.on('filesQueued', options.filesQueued);
				}

				if (options.uploadFinished) {
					uploader.on('uploadFinished', options.uploadFinished);
				}

				if (options.uploadBeforeSend) {
					uploader.on('uploadBeforeSend', options.uploadBeforeSend);
				}

				if (options.uploadError) {
					uploader.on('uploadError', options.uploadError);
				}

				if (options.uploadAccept) {
					uploader.on('uploadAccept', options.uploadAccept);
				}

				// 文件上传失败，显示上传出错
				uploader.on('error', function (msg, file) {

					var err = msg;
					switch (err) {
						case 'Q_EXCEED_SIZE_LIMIT':
							var size     = parseFloat((uploader.option("fileSizeLimit") / 1024 / 1024).toFixed(2));
							var sizeType = " M";
							if (size >= 1024) {
								size     = parseFloat((size / 1024).toFixed(2));
								sizeType = " G";
							}
							err = "所有文件的总大小不能超过 " + size + sizeType;
							break;
						case 'Q_TYPE_DENIED':
							if (file.size <= 0 && !this.options.accept) {
								err = '文件不能为空';
							} else
								err = "请上传指定的文件类型";
							break;
						case 'F_DUPLICATE':
							err = "此文件已上传,编辑完文件信息,保存即可";
							break;
						case 'Q_EXCEED_NUM_LIMIT':
							err = "每次最多传" + uploader.option("fileNumLimit") + "个文件，超出的文件会被忽略";
							break;
						case 'F_EXCEED_SIZE':
							var size     = parseFloat((uploader.option("fileSingleSizeLimit") / 1024 / 1024).toFixed(2));
							var sizeType = " M";
							if (size >= 1024) {
								size     = parseFloat((size / 1024).toFixed(2));
								sizeType = " G";
							}
							err = "文件大小不能超过 " + size + sizeType;
							break;

					}
					Hacfin.Dialog.ShowError(err);
				});

				return uploader;
			}
		}
		//endregion assets
	};
})(window);

//------------------------------------------------- Extend ------------------------------------------------------------//
//extend FileReader support >= IE10
if (typeof FileReader != "undefined" && !FileReader.prototype.readAsBinaryString) {
	FileReader.prototype.readAsBinaryString = function (fileData) {
		var binary = "";
		var pt     = this;

		var reader    = new FileReader();
		reader.onload = function (e) {
			var bytes  = new Uint8Array(e.target.result);
			var length = bytes.byteLength;
			for (var i = 0; i < length; i++) {
				binary += String.fromCharCode(bytes[i]);
			}

			//pt.result  - readonly so assign binary
			pt.content = binary;
			$(pt).trigger('onload');
		};
		reader.readAsArrayBuffer(fileData);
	}
}

// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
// 例子：
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
Date.prototype.Format = function (fmt) {
	var o    = {
		"M+": this.getMonth() + 1, //月份
		"d+": this.getDate(), //日
		"h+": this.getHours() % 12 == 0 ? 12 : this.getHours() % 12, //小时
		"H+": this.getHours(), //小时
		"m+": this.getMinutes(), //分
		"s+": this.getSeconds(), //秒
		"q+": Math.floor((this.getMonth() + 3) / 3), //季度
		"S" : this.getMilliseconds() //毫秒
	};
	var week = {
		"0": "/u65e5",
		"1": "/u4e00",
		"2": "/u4e8c",
		"3": "/u4e09",
		"4": "/u56db",
		"5": "/u4e94",
		"6": "/u516d"
	};
	if (/(y+)/.test(fmt)) {
		fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
	}
	if (/(E+)/.test(fmt)) {
		fmt = fmt.replace(RegExp.$1, ((RegExp.$1.length > 1) ? (RegExp.$1.length > 2 ? "/u661f/u671f" : "/u5468") : "") + week[this.getDay() + ""]);
	}
	for (var k in o) {
		if (new RegExp("(" + k + ")").test(fmt)) {
			fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
		}
	}

	return fmt;
};

/*
 扩展validator验证
 需要jquery.validate.min.js的支持
 */
jQuery.validatorEx = function () {

	//验证用户名
	jQuery.validator.addMethod("checkName", function (value, element) {
		var chrnumReg = /^([_a-zA-Z0-9]{4,20})$/;
		var numReg    = /^([0-9]+)$/;
		return this.optional(element) || (!numReg.test(value) && chrnumReg.test(value));
	}, "请输入由数字、字母、下划线组成的，非纯数字的，长度为4-20的字符串");

	//验证邮箱
	jQuery.validator.addMethod("checkEmail", function (value, element) {
		var myreg = /^[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]*)*@[a-zA-Z0-9\-]+([\.][a-zA-Z\-]+)+$/;
		return this.optional(element) || myreg.test(value);
	}, "请输入有效的邮箱地址");

	//验证密码
	jQuery.validator.addMethod("checkPassword", function (value, element) {
		var myreg = /((?=.*\d)(?=.*\D)|(?=.*[a-zA-Z])(?=.*[^a-zA-Z]))^[^\s]{6,20}$/;
		return this.optional(element) || myreg.test(value);
	}, "请输入由字母、数字、标点符号组成的字符串，但不能有空格");
	jQuery.validator.addMethod("condition", function (value, element, param) {
		return param;
	}, "不符合条件");

	//验证手机号
	jQuery.validator.addMethod("checkMobile", function (value, element) {
		var length = value.length;
		var mobile = /^1[34578]{1}[0-9]{9}$/;
		return this.optional(element) || (length == 11 && mobile.test(value));
	}, "请正确填写手机号码");
	//验证流地址
	jQuery.validator.addMethod("checkStreamname", function (value, element) {
		var chrnumReg = /^([_a-zA-Z0-9-]+)$/;

		return this.optional(element) || (chrnumReg.test(value));
	}, "请输入由数字、字母、下划线及破折号组成的");
};

/**
 * 设置radio与checkbox选中
 *
 * @param bChecked
 */
jQuery.fn.checked = function (bChecked) {
	if (bChecked) {
		this.attr("checked", 'checked');
		this.prop("checked", true);
	} else {
		this.removeAttr("checked");
	}
};