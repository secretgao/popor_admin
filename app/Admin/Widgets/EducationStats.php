<?php

namespace App\Admin\Widgets;

use App\Models\AdminUser;
use App\Models\User;
use App\Models\Course;
use App\Models\Invoice;
use Encore\Admin\Widgets\Widget;

class EducationStats extends Widget
{
    /**
     * 渲染小部件
     */
    public function render()
    {
        // 获取统计数据
        $teacherCount = AdminUser::where('is_del', false)
            ->whereHas('roles', function($query) {
                $query->where('slug', 'teacher');
            })->count(); // 教师角色用户数（未删除）
        $studentCount = User::where('is_active', true)->count(); // 学生用户数
        $courseCount = Course::count();
        $invoiceCount = Invoice::count();

        // 获取活跃用户数量
        $activeTeacherCount = AdminUser::where('is_del', false)
            ->whereHas('roles', function($query) {
                $query->where('slug', 'teacher');
            })->count(); // 活跃教师数
        $activeStudentCount = User::where('is_active', true)->count(); // 活跃学生数

        // 获取账单状态统计
        $pendingInvoices = Invoice::where('status', Invoice::STATUS_PENDING)->count();
        $processingInvoices = Invoice::where('status', Invoice::STATUS_PROCESSING)->count();
        $paidInvoices = Invoice::where('status', Invoice::STATUS_PAID)->count();
        $failedInvoices = Invoice::where('status', Invoice::STATUS_FAILED)->count();
        $totalAmount = Invoice::where('status', Invoice::STATUS_PAID)->sum('amount');

        return view('admin.widgets.education-stats', compact(
            'teacherCount',
            'studentCount',
            'courseCount',
            'invoiceCount',
            'activeTeacherCount',
            'activeStudentCount',
            'pendingInvoices',
            'processingInvoices',
            'paidInvoices',
            'failedInvoices',
            'totalAmount'
        ));
    }
}
