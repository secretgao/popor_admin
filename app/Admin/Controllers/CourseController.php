<?php

namespace App\Admin\Controllers;
use App\Models\Course;
use App\Models\AdminUser;
use App\Admin\Actions\CourseSoftDeleteAction;
use App\Admin\Actions\CourseRestoreAction;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '课程管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Course());

        $grid->column('id', 'ID')->sortable();
        $grid->column('name', '课程名称')->sortable();
        $grid->column('fee', '费用')->display(function ($fee) {
            return '¥' . number_format($fee, 2);
        });
        $grid->column('teacher.name', '授课教师');
        $grid->column('students_count', '学生数量')->display(function () {
            return $this->students()->count();
        });
        $grid->column('is_del', '删除状态')->display(function ($value) {
            return $value ? '<span class="label label-danger">已删除</span>' : '<span class="label label-success">正常</span>';
        })->sortable();
        $grid->column('created_at', '创建时间')->display(function () {
            return $this->formatted_created_at;
        })->sortable();
        $grid->column('updated_at', '更新时间')->display(function () {
            return $this->formatted_updated_at;
        })->sortable();

        // 搜索功能
        $grid->filter(function ($filter) {
            $filter->like('name', '课程名称');
            $filter->equal('teacher_id', '授课教师')->select(AdminUser::whereHas('roles', function ($query) {
            $query->where('slug', 'teacher');
        })->pluck('name', 'id'));
            $filter->like('year_month', '年月');
            $filter->equal('is_del', '删除状态')->select([
                '' => '全部',
                '0' => '正常',
                '1' => '已删除'
            ]);
        });

        // 操作按钮
        $grid->actions(function ($actions) {
            $actions->disableDelete(); // 禁用默认删除
            
            // 根据删除状态显示不同操作
            if ($actions->row->is_del) {
                // 已删除状态，显示恢复按钮
                $actions->add(new CourseRestoreAction());
            } else {
                // 正常状态，显示软删除按钮
                $actions->add(new CourseSoftDeleteAction());
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
        $show = new Show(Course::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('name', '课程名称');
        $show->field('year_month_display', '年月');
        $show->field('fee', '费用')->as(function ($fee) {
            return '¥' . number_format($fee, 2);
        });
        $show->field('teacher.name', '授课教师');
        $show->field('created_at', '创建时间')->as(function () {
            return $this->formatted_created_at;
        });
        $show->field('updated_at', '更新时间')->as(function () {
            return $this->formatted_updated_at;
        });

        // 显示课程的学生
        $show->students('选课学生', function ($students) {
            $students->setResource('/admin/students');
            $students->id('ID');
            $students->name('姓名');
            $students->email('邮箱');
            $students->created_at('选课时间');
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
        $form = new Form(new Course());

        $form->text('name', '课程名称')->required()->rules('required|string|max:200');
        $form->text('year_month', '年月')->required()->rules('required|string|size:6')->help('格式：202501');
        $form->currency('fee', '费用')->required()->rules('required|numeric|min:0')->symbol('¥');
        $form->select('teacher_id', '授课教师')->options(AdminUser::whereHas('roles', function ($query) {
            $query->where('slug', 'teacher');
        })->pluck('name', 'id'))->required();

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
        $course = Course::findOrFail($id);

        // 检查是否有学生选课
        if ($course->students()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => '该课程还有学生选课，无法删除！'
            ]);
        }

        // 检查是否有账单记录
        if ($course->invoices()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => '该课程还有账单记录，无法删除！'
            ]);
        }

        $course->delete();

        return response()->json([
            'status' => true,
            'message' => '删除成功！'
        ]);
    }
}
