<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\RowAction;

class RestoreAction extends RowAction
{
    public $name = '恢复';

    public function handle()
    {
        try {
            $model = $this->row;
            $model->restore();
            return $this->response()->success('恢复成功！')->redirect('/admin/teachers');
        } catch (\Exception $e) {
            return $this->response()->error('恢复失败：' . $e->getMessage());
        }
    }

    public function render()
    {
        return parent::render();
    }
}
