<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\RowAction;

class CourseSoftDeleteAction extends RowAction
{
    public $name = '软删除';

    public function handle()
    {
        try {
            $model = $this->row;
            $model->update(['is_del' => true]);
            return $this->response()->success('软删除成功！')->redirect('/admin/courses');
        } catch (\Exception $e) {
            return $this->response()->error('软删除失败：' . $e->getMessage());
        }
    }

    public function render()
    {
        return parent::render();
    }
}
