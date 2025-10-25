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
        $grid->column('course.name', '课程名称');
        $grid->column('student.name', '学生姓名');
        $grid->column('year_month', '年月');
        $grid->column('amount', '金额')->display(function ($amount) {
            return '¥' . number_format($amount, 2);
        });
        $grid->column('status_name', '状态')->display(function ($statusName) {
            $status = $this->status;
            $class = match($status) {
                Invoice::STATUS_PENDING => 'label-warning',
                Invoice::STATUS_SENT => 'label-info',
                Invoice::STATUS_PAID => 'label-success',
                Invoice::STATUS_CANCELLED => 'label-danger',
                default => 'label-default',
            };
            return "<span class='label {$class}'>{$statusName}</span>";
        });
        $grid->column('sent_at', '发送时间');
        $grid->column('paid_at', '支付时间');
        $grid->column('created_at', '创建时间')->sortable();

        // 搜索功能
        $grid->filter(function ($filter) {
            $filter->like('course.name', '课程名称');
            $filter->like('student.name', '学生姓名');
            $filter->equal('status', '状态')->select([
                Invoice::STATUS_PENDING => '待处理',
                Invoice::STATUS_SENT => '已发送',
                Invoice::STATUS_PAID => '已支付',
                Invoice::STATUS_CANCELLED => '已取消',
            ]);
            $filter->between('created_at', '创建时间')->date();
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
        $show->field('payment_method', '支付方式');
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
        $form->select('status', '状态')->options([
            Invoice::STATUS_PENDING => '待处理',
            Invoice::STATUS_SENT => '已发送',
            Invoice::STATUS_PAID => '已支付',
            Invoice::STATUS_CANCELLED => '已取消',
        ])->default(Invoice::STATUS_PENDING);
        $form->datetime('sent_at', '发送时间');
        $form->datetime('paid_at', '支付时间');
        $form->text('omise_charge_id', 'Omise Charge ID');
        $form->text('payment_method', '支付方式');
        $form->text('currency', '币种')->default('THB');

        // 禁用查看、创建和编辑检查
        $form->disableViewCheck();
        $form->disableCreatingCheck();
        $form->disableEditingCheck();

        return $form;
    }

    /**
     * 重写删除方法，使用软删除
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // 检查是否已支付
        if ($invoice->isPaid()) {
            return response()->json([
                'status' => false,
                'message' => '已支付的账单无法删除！'
            ]);
        }

        $invoice->delete();

        return response()->json([
            'status' => true,
            'message' => '删除成功！'
        ]);
    }
}
