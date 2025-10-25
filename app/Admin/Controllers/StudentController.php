<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Admin\Actions\StudentSoftDeleteAction;
use App\Admin\Actions\StudentRestoreAction;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StudentController extends BaseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '学生管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        // 显示所有用户（学生管理）

        $grid->column('id', 'ID')->sortable();
        $grid->column('username', '用户名')->sortable();
        $grid->column('name', '姓名')->sortable();
        $grid->column('email', '邮箱')->sortable();
        $grid->column('role_name', '角色')->display(function () {
            return '学生';
        });
        $grid->column('is_active', '状态')->display(function ($isActive) {
            return $isActive ? '<span class="label label-success">启用</span>' : '<span class="label label-danger">禁用</span>';
        });
        $grid->column('courses_count', '选课数量')->display(function () {
            return $this->courseStudents()->count();
        });
        $grid->column('invoices_count', '账单数量')->display(function () {
            return $this->invoices()->count();
        });
        $grid->column('created_at', '创建时间')->display(function () {
            return $this->formatted_created_at;
        })->sortable();
        $grid->column('updated_at', '更新时间')->display(function () {
            return $this->formatted_updated_at;
        })->sortable();

        // 搜索功能
        $grid->filter(function ($filter) {
            $filter->like('username', '用户名');
            $filter->like('name', '姓名');
            $filter->like('email', '邮箱');
            $filter->equal('is_active', '状态')->select([
                '' => '全部',
                1 => '启用',
                0 => '禁用'
            ]);
        });

        // 操作按钮
        $grid->actions(function ($actions) {
            $actions->disableDelete(); // 禁用默认删除
            
            // 根据状态显示不同操作
            if ($actions->row->is_active) {
                // 启用状态，显示软删除按钮
                $actions->add(new StudentSoftDeleteAction());
            } else {
                // 禁用状态，显示恢复按钮
                $actions->add(new StudentRestoreAction());
            }
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('username', '用户名');
        $show->field('name', '姓名');
        $show->field('email', '邮箱');
        $show->field('role_name', '角色')->as(function () {
            return '学生';
        });
        $show->field('is_active', '状态')->as(function ($isActive) {
            return $isActive ? '启用' : '禁用';
        });
        $show->field('created_at', '创建时间')->as(function () {
            return $this->formatted_created_at;
        });
        $show->field('updated_at', '更新时间')->as(function () {
            return $this->formatted_updated_at;
        });

        // 显示学生的选课
        $show->courseStudents('选课记录', function ($courseStudents) {
            $courseStudents->setResource('/admin/course-students');
            $courseStudents->id('ID');
            $courseStudents->course('课程')->name('课程名称');
            $courseStudents->created_at('选课时间');
        });

        // 显示学生的账单
        $show->invoices('账单记录', function ($invoices) {
            $invoices->setResource('/admin/invoices');
            $invoices->id('ID');
            $invoices->course('课程')->name('课程名称');
            $invoices->amount('金额');
            $invoices->status_name('状态');
            $invoices->created_at('创建时间');
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('username', '用户名')->required()->rules($this->getUsernameRules($form));
        $form->text('name', '姓名')->required()->rules('required|string|max:255');
        $form->email('email', '邮箱')->required()->rules($this->getEmailRules($form));
        $form->password('password', '密码')->rules($this->getPasswordRules($form));
        $form->switch('is_active', '状态')->default(1);

        // 移除role字段

        // 处理密码加密
        $this->handlePassword($form);

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
        $student = User::findOrFail($id);
        
        // 检查是否有选课记录
        if ($student->courseStudents()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => '该学生还有选课记录，无法删除！'
            ]);
        }

        // 检查是否有账单记录
        if ($student->invoices()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => '该学生还有账单记录，无法删除！'
            ]);
        }

        $student->delete();

        return response()->json([
            'status' => true,
            'message' => '删除成功！'
        ]);
    }
}
