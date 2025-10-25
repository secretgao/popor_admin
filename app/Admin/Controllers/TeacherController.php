<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\AdminUser;
use App\Admin\Actions\SoftDeleteAction;
use App\Admin\Actions\RestoreAction;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;

class TeacherController extends BaseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '教师管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdminUser());

        // 只显示教师角色 - 通过admin_roles表关联查询
        $grid->model()->whereHas('roles', function ($query) {
            $query->where('slug', 'teacher');
        });

        $grid->column('id', 'ID')->sortable();
        $grid->column('username', '用户名')->sortable();
        $grid->column('email', '邮箱')->sortable();
        $grid->column('is_del', '删除状态')->display(function ($value) {
            return $value ? '<span class="label label-danger">已删除</span>' : '<span class="label label-success">正常</span>';
        })->sortable();
        $grid->column('courses_count', '课程数量')->display(function () {
            try {
                return $this->courses()->count();
            } catch (\Exception $e) {
                return 0;
            }
        });
        $grid->column('created_at', '创建时间')->display(function () {
            try {
                return $this->formatted_created_at ?: '未知';
            } catch (\Exception $e) {
                return '未知';
            }
        })->sortable();
        $grid->column('updated_at', '更新时间')->display(function () {
            try {
                return $this->formatted_updated_at ?: '未知';
            } catch (\Exception $e) {
                return '未知';
            }
        })->sortable();

        // 搜索功能
        $grid->filter(function ($filter) {
            $filter->like('name', '姓名');
            $filter->like('email', '邮箱');
            $filter->equal('is_del', '删除状态')->select([
                '' => '全部',
                '0' => '正常',
                '1' => '已删除'
            ]);
        });

        // 禁用创建按钮--只能通过管理员创建
        $grid->disableCreateButton();

        // 禁用批量操作
        $grid->batchActions(function ($batch) {
            $batch->disableDelete(); // 禁用批量删除
        });

        // 操作按钮
        $grid->actions(function ($actions) {
            $actions->disableDelete(); // 禁用默认删除
            
            // 根据删除状态显示不同操作
            if ($actions->row->is_del) {
                // 已删除状态，显示恢复按钮
                $actions->add(new RestoreAction());
            } else {
                // 正常状态，显示软删除按钮
                $actions->add(new SoftDeleteAction());
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
        $show = new Show(AdminUser::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('username', '用户名');
        $show->field('email', '邮箱');
        $show->field('role_name', '角色')->as(function () {
            return '教师';
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

        // 显示教师的课程
        $show->courses('授课课程', function ($courses) {
            $courses->setResource('/admin/courses');
            $courses->id('ID');
            $courses->name('课程名称');
            $courses->year_month_display('年月');
            $courses->fee('费用');
            $courses->created_at('创建时间');
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
        $form = new Form(new AdminUser());

        $form->text('username', '用户名')->required()->rules('required|string|max:255');
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
        
        // 禁用删除按钮
        $form->tools(function ($tools) {
            $tools->disableDelete();
        });

        return $form;
    }

    /**
     * 重写删除方法
     */
    public function destroy($id)
    {
        $teacher = AdminUser::findOrFail($id);

        // 检查是否有课程
   //     if ($teacher->courses()->count() > 0) {
   //         return response()->json([
   //             'status' => false,
   //             'message' => '该教师还有课程，无法删除！'
   //         ]);
   //     }

        // 直接删除（admin_users表不支持软删除）
        $teacher->update(['is_del' => true]);

        return response()->json([
            'status' => true,
            'message' => '删除成功！'
        ]);
    }

    /**
     * 重写恢复方法
     */
    public function restore($id)
    {
        $teacher = AdminUser::findOrFail($id);
        $teacher->restore();

        return response()->json([
            'status' => true,
            'message' => '恢复成功！'
        ]);
    }

}
