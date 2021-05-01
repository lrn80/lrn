<?php
/**
 * Created by PhpStorm.
 * User: 李若宁
 * Date: 2019/11/8
 * Time: 13:44
 */
use think\Route;

Route::any('api/:version/admin/list','api/:version.admin/adminList'); //
Route::any('api/:version/email/getcode','api/:version.email/getCode'); // 发送邮件
Route::any('api/:version/admin/register','api/:version.admin/register'); //用户注册
Route::any('api/:version/admin/login','api/:version.admin/login'); //
Route::post('api/:version/admin/edit','api/:version.admin/edit'); //
Route::any('api/:version/admin/del','api/:version.admin/delete'); //

Route::any('api/:version/auth/list','api/:version.auth/authList'); //获取权限列表
Route::any('api/:version/auth/add','api/:version.auth/add');
Route::any('api/:version/auth/update','api/:version.auth/update');
Route::any('api/:version/auth/del','api/:version.auth/delete'); //

Route::any('api/:version/books/add','api/:version.books/addBooks');
Route::any('api/:version/books/list','api/:version.books/getBooksList');
Route::any('api/:version/books/update','api/:version.books/updateBooks');
Route::any('api/:version/books/search','api/:version.books/search');
Route::any('api/:version/detail/info','api/:version.detail/getBookInfo');

Route::any('api/:version/borrow/lead','api/:version.borrow/leadBook');
Route::any('api/:version/borrow/list','api/:version.borrow/borrowList');
Route::any('api/:version/borrow/search','api/:version.borrow/search');
Route::any('api/:version/borrow/return','api/:version.borrow/returnBook');
// 获得分组列表
Route::any('api/:version/group/list','api/:version.group/groupList');
// 破损管理
Route::any('api/:version/damage/add','api/:version.damage/addDamage');
Route::any('api/:version/damage/list','api/:version.damage/damageList');
Route::any('api/:version/damage/repair','api/:version.damage/repair');
Route::any('api/:version/damage/search','api/:version.damage/search');