// var APIServer = "http://auth.boolongo.com";
// var APIServer = "http://192.168.1.3:9999";
(function (window, undefined) {
	if (typeof (Hacfin) == "undefined") {
		Hacfin        = {};
		window.Hacfin = Hacfin;
	}
	if (typeof (Hacfin.Config) == "undefined") {
		Hacfin.Config = {};
	}

	Hacfin.Config = {
		API: {
			AuthActiveCode: APIServer + "/api/auth/activecode", //根据机器码获取激活码
			AuthGetList   : APIServer + "/api/auth/getlist",		//根据机器码获取激活码
			AuthDelete    : APIServer + "/api/auth/delete", //根据id删除授权信息
		}
	};
})(window);