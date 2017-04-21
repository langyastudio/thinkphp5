/**
 * JW Player - [vod live]
 */
(function (window, undefined) {
	if (typeof (Hacfin) == "undefined") {
		Hacfin        = {};
		window.Hacfin = Hacfin;
	}

	if (typeof (Hacfin.JWPlayer) == "undefined") {
		Hacfin.JWPlayer = {};
	}

	Hacfin.JWPlayer = {
		/**
		 * @param el                HTML元素id名称
		 * @param fid                文件id号
		 * @param resolution        低分辨率地址
		 * @param high_resolution    高分辨率地址
		 * @param bIsLive            是否是直播
		 */
		Setup      : function (el, fid, resolution, high_resolution, bIsLive) {
			jwplayer.logo = "/public/assets/jwplayer/logo.png";
			jwplayer.url  = "http://hacfin.com";

			var setup = {
				primary     : "html5",
				flashplayer : "/public/assets/jwplayer/jwplayer.flash.swf",
				image       : "/public/assets/jwplayer/player.jpg",
				skin        : "/public/assets/jwplayer/skins/bekle.xml",
				bufferlength: 5,
				title       : 'Hacfin',
				description : "http://hacfin.com",
				controlbar  : "over",  //控制条位置
				screencolor : "#fff",  //播放器颜色
				stretching  : "fill",  //画面自适应
				// repeat     : "always", //重复播放
				autostart   : true,     //自动播放
				width       : "100%",
				aspectratio : "16:9",

				//modes配置项被用来指定渲染播放器不同浏览器技术的顺序
				modes: [
					{type: 'html5'},
					{type: 'flash', 'src': '/public/assets/jwplayer/jwplayer.flash.swf'},
					{type: 'download'}
				]

				//		tracks: [{
				//		  file: "video.txt",
				//		  //kind: "chapters"   //时间轴说明
				//		  kind: "captions"     //字幕
				//		}],

				//		captions: {            //字幕设置
				//		 color: "#FF0000",
				//		 fontSize: 24,
				//		 backgroundOpacity: 50
				//		},

				//		playlist: [
				//		 { duration: 32, title: "Sintel Trailer1", description: "SintSintelel Trailer1", file: "video.mp4", image:"player.jpg"},
				//		 { duration: 124, title: "Sintel Trailer2", description: "SSintelintel Trailer2",  file: "video.mp4", image:"player.jpg"},
				//		 { duration: 542, title: "Sintel Trailer3", description: "SiSintelntel Trailer3",  file: "video.mp4", image:"player.jpg"}
				//		],

				//		listbar: {
				//		 position: "right",
				//		 size: 240
				//		},

				//		plugins: {
				//		 hd: { file:  "/videos.mp4", fullscreen: true },
				//		 gapro: { accountid:  "UKsi93X940-24" }
				//		},
			};

			//1.0 点播
			if (!bIsLive) {
				//记录播放进度时间点
				setup.events = {
					onReady: function () {
						var sT     = Hacfin.Common.GetLocalStorage('videoplay_time_' + fid);
						var offset = 0;
						if (!isNaN(sT)) {
							offset = sT;
						} else {
							offset = 0;
						}

						//是否在末尾
						if (jwplayer(el).getDuration() - offset < 10)
							offset = 0;
						jwplayer(el).seek(offset);
					},
					onTime : function () {
						var pos = jwplayer(el).getPosition();
						Hacfin.Common.SetLocalStorage('videoplay_time_' + fid, pos);
					}
				};

				//多分辨率
				setup.sources = [{
					bitrate: 500, label: "标清", file: resolution
				}, {
					bitrate: 1500, label: "高清", file: high_resolution
				}];

				setup.provider = "http";
			} else {
				setup.file = resolution;

				setup.events = {
					onIdle: function () {
						// 说明不是用户点击了暂停
						if (jwplayer(el).getState() != 'PAUSED') {
							jwplayer(el).play();
						}
					}
				};
				if (resolution.indexOf('rtmp') >= 0) {//RTMP
					//可用值的范围是从 0.1 (对于低延时的直播) 到 10 (为防止频繁的重缓冲)。
					setup.rtmp = {
						bufferlength: 0.1
					};

					setup.provider = "rtmp";
				} else {//HTTP
					setup.provider = "http";
				}
			}

			jwplayer(el).setup(setup);
		},
		/**
		 * Load 新数据
		 * @constructor
		 */
		Load       : function (el, resolution) {
			jwplayer(el).load([{
				file: resolution
			}]);

			jwplayer(el).play();
		},
		/**
		 * 空格控制播放暂停
		 * <body onKeyDown="KeyOn_Space(event);">
		 *
		 * @param el
		 * @param evt
		 */
		KeyOn_Space: function(el, evt) {
			evt      = evt || window.event;
			var code = (evt.keyCode || evt.which);
			if (code == 32) {
				jwplayer(el).pause();
			}
		}
	};
})(window);
