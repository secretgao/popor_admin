<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\RowAction;

class SoftDeleteAction extends RowAction
{
    public $name = '软删除';

    public function handle()
    {
        try {
            $model = $this->row;
            $model->softDelete();
            
            // 尝试方法1: 使用redirect()
            return $this->response()->success('软删除成功！')->redirect('/admin/teachers');
        } catch (\Exception $e) {
            return $this->response()->error('软删除失败：' . $e->getMessage());
        }
    }

    public function render()
    {
        return parent::render();
    }
}
