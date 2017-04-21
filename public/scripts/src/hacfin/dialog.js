/**
 * 通用js处理函数，与项目无关
 */
var isSyn = false;
(function (window, undefined) {
	if (typeof (Hacfin) == "undefined") {
		Hacfin        = {};
		window.Hacfin = Hacfin;
	}

	if (typeof (Hacfin.Dialog) == "undefined") {
		Hacfin.Dialog = {};
	}

	Hacfin.Dialog = {
		//======================================== 【1】消息框 START =======================================================//
		//依赖toastr.js

		/***
		 * Toastr 消息框
		 * @param msg       消息
		 * @param type      类型
		 *         1 => success
		 *         2 => info
		 *         3 => warning
		 *         4 => error
		 * @constructor
		 */
		ToastrTip: function (msg, type) {
			toastr.options = {
				closeButton: true,
				progressBar: true,
				newestOnTop: true,
				showMethod : 'slideDown',
				timeOut    : 4000
			};

			// Remove current toasts using animation
			toastr.clear();

			switch (type) {
				case 1:
					return toastr.success(msg);
				case 2:
					return toastr.info(msg);
				case 3:
					return toastr.warning(msg);
				case 4:
					return toastr.error(msg);
			}
		},

		ShowSuccess: function (msg) {
			this.ToastrTip(msg, 1);
		},

		ShowInfo: function (msg) {
			this.ToastrTip(msg, 2);
		},

		ShowError: function (msg) {
			//警告信息自动添加中文 ！号
			if (msg) {
				var chr = msg.charAt(msg.length - 1);
				if (chr == '！') {

				} else if (chr == '!') {
					msg = msg.substr(0, msg.length - 1) + '！';
				} else
					msg += '！';
			}

			this.ToastrTip(msg, 4);
		},
		//======================================== 消息框 End =======================================================//


		//======================================== 【2】Tips START =======================================================//
		//依赖layer.js -- 分为PC与Mobile端

		/**
		 * 会话气泡 基于jquery.js 和 layer.js
		 * @param  msg string 消息
		 * @param selecter string 选择器,气泡附着在何处 如"#id",".class"
		 * @param where int 气泡方位 1,上;2,右;3,下;4,左
		 * @param time int 持续时间(ms)
		 * @returns int layer句柄
		 */
		Tips: function (msg, selecter, where, time) {
			if (!time) {
				time = 1000;
			} else if (time <= 0) {
				time = 1000 * 3600 * 24;
			}
			if (Hacfin.Common.IsPC()) {
				if (!where) {
					where = 1;
				}

				return layer.tips(msg, selecter, {tips: [where, "#1DB5FF"], tipsMore: true, time: time});
			} else {
				//提示
				return layer.open({
					content: msg
					, skin : 'msg'
					, time : time //time秒后自动关闭
				});
			}
		},
		//======================================== Tips End =======================================================//


		//======================================== 【3】Confirm START =======================================================//
		//依赖layer.js -- 分为PC与Mobile端

		/**
		 * 操作确认(不阻塞后面的代码执行; 基于layer.js)
		 * @param msg string 提示信息
		 * @param yFn function 确定后执行的操作
		 * @param nFn function 取消后执行的操作,可选
		 * @param btn1Name string 第一个按钮的名称,可选
		 * @param btn2Name string 第二个按钮的名称,可选
		 * @constructor
		 */
		Confirm: function (msg, yFn, nFn, btn1Name, btn2Name) {
			if (Hacfin.Common.IsPC()) {
				var option = {icon: 3, title: '操作提示', shift: 3,success:function(layero,index){
                    $(document).off('keydown').on('keydown',function(e){
                    	if(e.keyCode == 13){
                            yFn();
                            layer.close(index);
                            return false; //阻止系统默认回车事件
						}
					})

				},
				end:function(layero,index){
					$(document).off('keydown').on('keydown',function(e){
						if(e.keyCode == 13){
							return false; //阻止系统默认回车事件
						}
					});
				}};
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
			} else {

				if (!btn1Name) {
					btn1Name = "确定";
				}
				if (!btn2Name) {
					btn2Name = "取消";
				}

				var btn = [btn1Name, btn2Name];

				//底部对话框
				layer.open({
					title  : '操作提示',
					content: msg,
					btn    : btn,
					skin   : 'footer',  //或者msg
					yes    : function (index) {//确定
						yFn();
						layer.close(index);
					},
					no     : function (index) {//取消
						if (nFn) {
							nFn();
						}
						layer.close(index);
					}
				});
			}
		},
		//======================================== Confirm End =======================================================//


		//======================================== 【4】iframe START ==============================================//
        /**
		 *
         * @param el 元素对象
         */
        MagnificPopup:function(el){
            var magnificPopup = $.magnificPopup.instance;
            //=================================== 说明 START =================================================//
            //1.0 Content Types：
            //	image, iframe, inline, and ajax
            //
            //2.0 可以自定义iframe
            // http://dimsemenov.com/plugins/magnific-popup/documentation.html#iframe-type
            // http://codepen.io/dimsemenov/pen/jnohA
            //=================================== 说明 Ender =================================================//
            //From the ‘items’ option
            //
            //embed分享时，可以使用
            //<iframe src="http://*****/embed/sewisevideo?fid=2" width="640" height="360" frameborder="0"
            // webkitallowfullscreen mozallowfullscreen allowfullscreen>
            //</iframe>
            $(el).magnificPopup({
                type: 'image',
                items:[{
                    src: "",
                    type: 'iframe'

                }],
                callbacks: {
                    beforeOpen: function() {
						if($('#mModal').length>0){
                            $('#mModal').hide()
						}
                        var fid =  magnificPopup.st.el.attr('fid');
                        var type = magnificPopup.st.el.attr('type');
                        magnificPopup.items[0].src = '/embed/'+type+'?fid='+fid+'';
                    },
                    afterClose: function() {
                        if($('#mModal').length>0){
                            $('#mModal').show()
                        }
                    },
                }
            })

		},


		//======================================== Load End ==============================================//

		//======================================== 【5】Load START ==============================================//
		//依赖layer.js -- 分为PC与Mobile端
		Loading     : function (isAlwayShow) {
			if (Hacfin.Common.IsPC()) {
				if (isAlwayShow) {
					return layer.load(0, {
						shade: [0.1, '#000'],
					});
				} else {
					return layer.load(0, {
						time : 10 * 1000,
						shade: [0.1, '#000'],
					});
				}
			} else {
				//loading带文字
				return layer.open({
					type     : 2
					, content: '加载中'
				});
			}
		},
		LoadingClose: function (layid) {
			layer.close(layid);
		}
		//======================================== Load End ==============================================//
	};
})(window);

/**
 * layer全局配置，使用自定义皮肤
 */
if(Hacfin.Common.IsPC()){
    layer.config({
        extend: 'modal/style.css',
        skin: 'layer-ext-modal' //默认皮肤
    });
}


