<?php

return [
    // 系统名称
    'system_name' => '教育管理系统',
    'dashboard' => '仪表盘',
    
    // 用户管理
    'users' => '用户管理',
    'teachers' => '教师管理',
    'students' => '学生管理',
    'teacher' => '教师',
    'student' => '学生',
    'admin' => '管理员',
    
    // 课程管理
    'courses' => '课程管理',
    'course' => '课程',
    'course_name' => '课程名称',
    'course_fee' => '课程费用',
    'year_month' => '年月',
    
    // 账单管理
    'invoices' => '账单管理',
    'invoice' => '账单',
    'amount' => '金额',
    'description' => '描述',
    'payment_status' => '支付状态',
    
    // 状态
    'status' => '状态',
    'pending' => '待支付',
    'processing' => '支付中',
    'paid' => '支付成功',
    'failed' => '支付失败',
    'refunded' => '已退款',
    'active' => '活跃',
    'inactive' => '非活跃',
    'enabled' => '启用',
    'disabled' => '禁用',
    
    // 统计信息
    'statistics' => '统计信息',
    'total_teachers' => '教师总数',
    'total_students' => '学生总数',
    'total_courses' => '课程总数',
    'total_invoices' => '账单总数',
    'active_teachers' => '活跃教师',
    'active_students' => '活跃学生',
    'total_revenue' => '总收入',
    'user_status_stats' => '用户状态统计',
    'invoice_status_stats' => '账单状态统计',
    
    // 操作
    'actions' => '操作',
    'quick_actions' => '快速操作',
    'add_student' => '添加学生',
    'create_course' => '创建课程',
    'more_info' => '更多信息',
    
    // Webhook
    'webhook_events' => 'Webhook 事件',
    'webhook_event' => 'Webhook 事件',
    'event_id' => '事件ID',
    'event_type' => '事件类型',
    'payload' => '载荷',
    'process_status' => '处理状态',
    'processed_at' => '处理时间',
    'error_message' => '错误信息',
    
    // 通用字段
    'id' => 'ID',
    'name' => '姓名',
    'username' => '用户名',
    'email' => '邮箱',
    'password' => '密码',
    'created_at' => '创建时间',
    'updated_at' => '更新时间',
    'deleted_at' => '删除时间',
    
    // 消息
    'success' => '成功',
    'error' => '错误',
    'warning' => '警告',
    'info' => '信息',
    'confirm' => '确认',
    'cancel' => '取消',
    'save' => '保存',
    'edit' => '编辑',
    'delete' => '删除',
    'view' => '查看',
    'create' => '创建',
    'update' => '更新',
    'back' => '返回',
    'submit' => '提交',
    'reset' => '重置',
    'search' => '搜索',
    'filter' => '筛选',
    'export' => '导出',
    'import' => '导入',
    
    // 分页
    'pagination' => [
        'previous' => '上一页',
        'next' => '下一页',
        'showing' => '显示',
        'to' => '到',
        'of' => '共',
        'results' => '条结果',
    ],
    
    // 表单验证
    'validation' => [
        'required' => '此字段为必填项',
        'email' => '请输入有效的邮箱地址',
        'min' => '最少需要 :min 个字符',
        'max' => '最多允许 :max 个字符',
        'unique' => '此值已存在',
        'numeric' => '请输入数字',
        'date' => '请输入有效的日期',
    ],
];
