<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


//加载后台登录页面
Route::rule('/admin/login', 'admin/LoginController/login');
//后台登录时，检测用户名是否存在
Route::rule('/admin/search_name', 'admin/UserController/search_name');
//后台添加用户时，检测用户名是否存在
Route::rule('/admin/search_username', 'admin/UserController/search_username');
//后台添加管理员时，检测用户名是否存在
Route::rule('/admin/search_aname', 'admin/AdminController/search_aname');
//后台添加友情链接时，检测链接名是否存在
Route::rule('/admin/search_friendname', 'admin/FriendController/search_friendname');
//显示验证码
Route::rule('/admin/code', 'admin/LoginController/code');
//执行登录
Route::rule('/admin/dologin', 'admin/LoginController/dologin');
//退出登录
Route::rule('/admin/login_out', 'admin/LoginController/login_out');
//加载后台首页
Route::get('/admin/index', 'admin/LoginController/index')->middleware('CheckAdmin');


//加载前台登录页面
Route::rule('/home/login', 'home/LoginController/login');
//前台登录时，检测用户名是否存在
Route::rule('/home/search_name', 'home/LoginController/search_name');
//显示验证码
Route::rule('/home/code', 'home/LoginController/code');
//执行登录
Route::rule('/home/dologin', 'home/LoginController/dologin');
//退出登录
Route::rule('/home/login_out', 'home/LoginController/login_out');
//加载前台首页
Route::get('/home/index', 'home/IndexController/index');


//用户管理
Route::group(['name'=>'/admin/','prefix'=>'admin/UserController/'],function(){
	//加载后台添加用户页面
	Route::rule('user_add', 'useradd');

	//加载后台首页
	Route::get('admin_index', 'index');

	//执行添加用户的方法
	Route::rule('user_save', 'save');

	//加载用户列表页面
	Route::rule('user_list', 'userlist');

	//删除用户的方法
	Route::rule('user_delete/:id', 'delete');

	//修改用户的方法
	Route::rule('user_edit/:id', 'edit');

	//执行修改用户的方法
	Route::rule('user_update/:id', 'update');

	//修改用户密码的方法
	Route::rule('user_pwd/:id', 'pwd');

	//执行修改用户密码的方法
	Route::rule('user_updatepwd/:id', 'updatepwd');

	//用户禁用启用的方法
	Route::rule('user_jq/:id', 'jq');

	//禁用启用成功后跳转的页面
	Route::rule('user_jinqi', 'jinqi');

	//用户禁用启用的方法
	Route::rule('user_recycle/:id', 'recycle');
})->middleware('CheckAdmin');

//分类管理
Route::group(['name'=>'/admin/','prefix'=>'admin/TypeController/'],function(){
	//显示浏览分类的方法
	Route::rule('type_list','typelist','get');

	//添加分类的方法
	Route::rule('type_add/[:id]','create');

	//执行添加分类的方法
	Route::rule('type_save','save');

	//删除分类的方法
	Route::rule('type_delete/:id','delete');

	//修改分类的方法（加载修改页面）
	Route::rule('type_edit/:id','edit');

	//执行修改分类的方法
	Route::rule('type_update/:id','update');
})->middleware('CheckAdmin');

//商品管理
Route::group(['name'=>'/admin/','prefix'=>'admin/GoodsController/'],function(){
	//显示浏览分类的方法
	Route::rule('goods_list','goodslist','get');

	//添加分类的方法
	Route::rule('goods_add','create');

	//执行添加分类的方法
	Route::rule('goods_save','save');

	//删除分类的方法
	Route::rule('goods_delete/:id','delete');

	//修改分类的方法（加载修改页面）
	Route::rule('goods_edit/:id','edit');

	//执行修改分类的方法
	Route::rule('goods_update/:id','update');
})->middleware('CheckAdmin');

//管理员管理
Route::group(['name'=>'/admin/','prefix'=>'admin/AdminController/'],function(){
	//添加管理员的方法
	Route::rule('admin_add','create');

	//执行添加管理员的方法
	Route::rule('admin_save','save');
	
	//显示浏览管理员的方法
	Route::rule('admin_list','adminlist');

	//删除管理员的方法
	Route::rule('admin_delete/:id','delete');

	//修改管理员的方法（加载修改页面）
	Route::rule('admin_edit/:id','edit');

	//执行修改管理员的方法
	Route::rule('admin_update/:id','update');

	//修改管理员密码的方法
	Route::rule('admin_pwd/:id', 'pwd');

	//执行修改管理员密码的方法
	Route::rule('admin_updatepwd/:id', 'updatepwd');

	//管理员禁用启用的方法
	Route::rule('admin_jq/:id', 'jq');

	//禁用启用成功后跳转的页面
	Route::rule('admin_jinqi', 'jinqi');

	//管理员回收站的方法
	Route::rule('admin_recycle/:id', 'recycle');
})->middleware('CheckAdmin');


//网站配置管理
Route::group(['name'=>'/admin/','prefix'=>'admin/ConfigController/'],function(){
	//添加网站配置的方法
	Route::rule('config_add','create');

	//执行添加网站配置的方法
	Route::rule('config_save','save');
	
	//显示浏览网站配置的方法
	Route::rule('config_list','configlist');

	//删除网站配置的方法
	Route::rule('config_delete/:id','delete');

	//修改网站配置的方法（加载修改页面）
	Route::rule('config_edit/:id','edit');

	//执行修改网站配置的方法
	Route::rule('config_update/:id','update');

	//网站配置禁用启用的方法
	Route::rule('config_jq/:id', 'jq');

	//禁用启用成功后跳转的页面
	Route::rule('config_jinqi', 'jinqi');

	//网站配置回收站的方法
	Route::rule('config_recycle/:id', 'recycle');
})->middleware('CheckAdmin');

//友情链接模块
Route::group(['name'=>'/admin/','prefix'=>'admin/FriendController/'],function(){
	//添加友情链接的方法
	Route::rule('friend_add','create');

	//执行添加友情链接的方法
	Route::rule('friend_save','save');
	
	//显示浏览友情链接的方法
	Route::rule('friend_list','friendlist');

	//删除友情链接的方法
	Route::rule('friend_delete/:id','delete');

	//修改友情链接的方法（加载修改页面）
	Route::rule('friend_edit/:id','edit');

	//执行修改友情链接的方法
	Route::rule('friend_update/:id','update');

	//友情链接禁用启用的方法
	Route::rule('friend_jq/:id', 'jq');

	//禁用启用成功后跳转的页面
	Route::rule('friend_jinqi', 'jinqi');

	//友情链接回收站的方法
	Route::rule('friend_recycle/:id', 'recycle');
})->middleware('CheckAdmin');

//前台注册管理
Route::group(['name'=>'/home/','prefix'=>'home/LoginController/'],function(){
	//加载注册页面的方法（路由）
	Route::rule('register','register');
	//注册时判断用户名是否已经存在
	Route::rule('register_name','register_name');
});