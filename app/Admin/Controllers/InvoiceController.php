<?php

namespace App\Admin\Controllers;

use App\Models\Invoice;
use App\Models\Course;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class InvoiceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '账单管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Invoice());

        $grid->column('id', 'ID')->sortable();
        $grid->column('student_id', '学生ID');
        $grid->column('course_id', '课程ID');
        $grid->column('teacher_id', '教师ID');
        $grid->column('course.name', '课程名称');
        $grid->column('student.name', '学生姓名');
        $grid->column('year_month', '年月');
        $grid->column('amount', '金额')->display(function ($amount) {
            return '¥' . number_format($amount, 2);
        });
        $grid->column('description', '账单描述')->display(function ($description) {
            return $description ? (strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description) : '-';
        });
        $grid->column('status', '状态')->display(function ($status) {
            $statusName = match($status) {
                0 => '待发送',
                1 => '待支付',
                2 => '支付中',
                3 => '支付成功',
                4 => '支付失败',
                default => '未知状态',
            };
            $class = match($status) {
                0 => 'label-default',    // 待发送
                1 => 'label-warning',    // 待支付
                2 => 'label-info',       // 支付中
                3 => 'label-success',    // 支付成功
                4 => 'label-danger',     // 支付失败
                default => 'label-default',
            };
            return "<span class='label {$class}'>{$statusName}</span>";
        });
        $grid->column('formatted_paid_at', '支付时间');
        $grid->column('omise_charge_id', 'Omise Charge ID');
        $grid->column('currency', '币种');
        $grid->column('payment_success', '支付成功')->display(function ($success) {
            return $success ? '<span class="label label-success">是</span>' : '<span class="label label-danger">否</span>';
        });
        $grid->column('payment_status', '支付状态');
        $grid->column('payment_transaction_id', '交易ID');
        $grid->column('payment_error_message', '错误信息')->display(function ($error) {
            return $error ?: '-';
        });
        $grid->column('formatted_payment_processed_at', '处理时间');
        $grid->column('formatted_created_at', '创建时间')->sortable();
        $grid->column('formatted_updated_at', '更新时间');

        // 搜索功能
        $grid->filter(function ($filter) {
            $filter->like('course.name', '课程名称');
            $filter->like('student.name', '学生姓名');
            $filter->equal('status', '状态')->select([
                0 => '待发送',
                1 => '待支付',
                2 => '支付中',
                3 => '支付成功',
                4 => '支付失败',
            ]);
            $filter->between('created_at', '创建时间')->date();
        });

        // 禁用创建按钮
        $grid->disableCreateButton();
        
        // 禁用编辑按钮，启用删除按钮
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            // 启用删除操作
            $actions->enableDelete();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Invoice::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('course.name', '课程名称');
        $show->field('student.name', '学生姓名');
        $show->field('year_month', '年月');
        $show->field('amount', '金额')->as(function ($amount) {
            return '¥' . number_format($amount, 2);
        });
        $show->field('status_name', '状态');
        $show->field('sent_at', '发送时间');
        $show->field('paid_at', '支付时间');
        $show->field('omise_charge_id', 'Omise Charge ID');
        $show->field('currency', '币种');
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Invoice());

        $form->select('course_id', '课程')->options(Course::pluck('name', 'id'))->required();
        $form->select('student_id', '学生')->options(User::pluck('name', 'id'))->required();
        $form->text('year_month', '年月')->required()->rules('required|string|size:6')->help('格式：202501');
        $form->currency('amount', '金额')->required()->rules('required|numeric|min:0')->symbol('¥');
        $form->textarea('description', '账单描述')->rows(3);
        $form->select('status', '状态')->options([
            0 => '待发送',
            1 => '待支付',
            2 => '支付中',
            3 => '支付成功',
            4 => '支付失败',
        ])->default(0);
        $form->datetime('paid_at', '支付时间');
        $form->text('omise_charge_id', 'Omise Charge ID');
        $form->text('currency', '币种')->default('JPY');

        // 禁用查看、创建和编辑检查，启用删除检查
        $form->disableViewCheck();
        $form->disableCreatingCheck();
        $form->disableEditingCheck();
        $form->disableDeletingCheck();

        return $form;
    }

    /**
     * 重写删除方法，使用真实物理删除
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // 真实物理删除数据
        $invoice->delete();

        return response()->json([
            'status' => true,
            'message' => '删除成功！'
        ]);
    }
}
