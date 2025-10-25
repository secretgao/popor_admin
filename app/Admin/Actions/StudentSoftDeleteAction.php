<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\RowAction;

class StudentSoftDeleteAction extends RowAction
{
    public $name = '软删除';

    public function handle()
    {
        try {
            $model = $this->row;
            $model->update(['is_active' => false]);
            return $this->response()->success('软删除成功！')->redirect('/admin/students');
        } catch (\Exception $e) {
            return $this->response()->error('软删除失败：' . $e->getMessage());
        }
    }

    public function render()
    {
        return parent::render();
    }
}
