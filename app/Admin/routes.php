<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    
    // 教育管理路由（暂时注释，等控制器创建后再启用）
    // $router->resource('teachers', 'TeacherController');
    // $router->resource('students', 'StudentController');
    // $router->resource('courses', 'CourseController');
    // $router->resource('invoices', 'InvoiceController');

});
