var gulp      = require('gulp'),
	compass   = require('gulp-compass'),     //compass编译Sass
	sass 	  = require("gulp-sass"),		 //sass编译
	htmlmin   = require('gulp-htmlmin'),     //HTML压缩
	minifycss = require('gulp-minify-css'),  //css压缩
	concat    = require('gulp-concat'),		 //js合并
	uglify    = require('gulp-uglify'),		 //js压缩
	rename    = require('gulp-rename'),		 //重命名
	jshint    = require('gulp-jshint'),      //js代码检测
	del 	  = require('del');				 //删除文件夹里的内容
// “src/a.js”：指定具体文件
// “*”：匹配所有文件 例：src/*.js(包含src下的所有js文件)
//  “**”：匹配0个或多个子文件夹 例：src/**/*.js(包含src的0个或多个子文件夹下的js文件)
// “{}”：匹配多个属性 例：src/{a,b}.js(包含a.js和b.js文件) src/*.{jpg,png,gif}(src下的所有jpg/png/gif文件)
//  “!”：排除文件 例：!src/a.js(不包含src下的a.js文件)


//【1】语法检查
gulp.task('jshint', function () {
	return gulp.src('public/scripts/src/**/*.js')
			.pipe(jshint())
			.pipe(jshint.reporter('default'));
});

//【2】创建Compass任务，编译Sass生成到css、压缩css生成到dist
gulp.task('compass', function() {
	gulp.src('public/css/sass/**/*.scss')
			.pipe(compass({
				config_file: 'public/css/config.rb', // 配置文件
				css: 'public/css/css',      // 编译路径
				sass: 'public/css/sass'     // sass路径
			}))
			.pipe(gulp.dest('public/css/css'))  //输出到文件夹
			.pipe(minifycss({compatibility: 'ie8'}))  //压缩CSS
			.pipe(rename({suffix: '.min'}))     //rename压缩后的文件名
			.pipe(gulp.dest('public/css/dist')) // 发布到线上版本
			;
});

//【3】压缩HTML
// 1.collapseWhitespace:从字面意思应该可以看出来，清除空格，压缩html，这一条比较重要，作用比较大，引起的改变压缩量也特别大；
// 2.collapseBooleanAttributes:省略布尔属性的值，比如：<input checked="checked"/>,那么设置这个属性后，就会变成 <input checked/>;
// 3.removeComments:清除html中注释的部分，我们应该减少html页面中的注释。
// 4.removeEmptyAttributes:清除所有的空属性，
// 5.removeSciptTypeAttributes:清除所有script标签中的type="text/javascript"属性。
// 6.removeStyleLinkTypeAttributes:清楚所有Link标签上的type属性。
// 7.minifyJS:压缩html中的javascript代码。
// 8.minifyCSS:压缩html中的css代码。
// gulp.task('html', function () {
// 	var options = {
// 		collapseWhitespace           : true,
// 		collapseBooleanAttributes    : true,
// 		removeComments               : false,
// 		removeEmptyAttributes        : true,
// 		removeScriptTypeAttributes   : true,
// 		removeStyleLinkTypeAttributes: true,
// 		minifyJS                     : true,
// 		minifyCSS                    : true
// 	};
// 	gulp.src('app/**/*.html')
// 			.pipe(htmlmin(options))
// 			.pipe(gulp.dest('dest/'));
// });

//【4】压缩、合并 js
gulp.task('minifyjs', function () {
	return gulp.src('public/scripts/src/**/*.js') //需要操作的文件
			// .pipe(concat('main.js'))    //合并所有js到main.js
			// .pipe(gulp.dest('public/scripts/dist'))  //输出到文件夹
			.pipe(rename({suffix: '.min'}))   //rename压缩后的文件名
			.pipe(uglify())    //压缩
			.pipe(gulp.dest('public/scripts/dist'));  //输出
});

//执行压缩前，先删除文件夹里的内容
gulp.task('clean', function(cb) {
	del(['public/scripts/dist/*', 'public/css/dist/*', 'public/css/css/*'], cb)
});

//【5】监视文件的变化
gulp.task('watch', function () {
	gulp.watch('public/scripts/src/**/*.js', ['minifyjs']);
	gulp.watch('public/css/sass/**/*.scss', ['compass']);
});

//【6】默认命令，在cmd中输入gulp后，执行的就是这个任务(压缩js需要在检查js之后操作)
gulp.task('default', ['jshint', 'compass', 'watch'], function () {
});