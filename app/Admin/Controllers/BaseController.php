<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;

/**
 * 基础控制器
 * 提供通用的验证规则和方法
 */
abstract class BaseController extends AdminController
{
    /**
     * 获取邮箱验证规则
     * 兼容 PostgreSQL 数据库
     */
    protected function getEmailRules($form, $table = 'users', $column = 'email')
    {
        return function ($form) use ($table, $column) {
            $rules = 'required|email';
            if ($form->isEditing()) {
                $rules .= '|unique:' . $table . ',' . $column . ',' . $form->model()->id;
            } else {
                $rules .= '|unique:' . $table . ',' . $column;
            }
            return $rules;
        };
    }

    /**
     * 获取用户名验证规则
     * 兼容 PostgreSQL 数据库
     */
    protected function getUsernameRules($form, $table = 'users', $column = 'username')
    {
        return function ($form) use ($table, $column) {
            return 'required|string|max:255';
        };
    }

    /**
     * 获取密码验证规则
     */
    protected function getPasswordRules($form)
    {
        return function ($form) {
            if ($form->isEditing()) {
                // 编辑时密码可选
                return 'nullable|min:6';
            } else {
                // 创建时密码必填
                return 'required|min:6';
            }
        };
    }

    /**
     * 处理密码加密
     */
    protected function handlePassword($form)
    {
        $form->saving(function ($form) {
            if ($form->password) {
                $form->password = \Illuminate\Support\Facades\Hash::make($form->password);
            } elseif ($form->isEditing()) {
                // 编辑时如果没有输入密码，保持原密码
                $form->password = $form->model()->password;
            }
        });
    }
}
