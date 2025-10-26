<?php

namespace App\Admin\Controllers;

use App\Models\WebhookEvent;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class WebhookEventController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Webhook 事件管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WebhookEvent());

        // 设置默认排序
        $grid->model()->orderBy('created_at', 'desc');

        // 添加筛选器
        $grid->filter(function ($filter) {
            $filter->like('event_id', '事件ID');
            $filter->equal('type', '事件类型')->select([
                'charge.complete' => '支付完成',
                'charge.failed' => '支付失败',
                'refund.created' => '退款创建',
            ]);
            $filter->equal('process_status', '处理状态')->select([
                WebhookEvent::STATUS_PENDING => '待处理',
                WebhookEvent::STATUS_PROCESSED => '已处理',
                WebhookEvent::STATUS_FAILED => '处理失败',
            ]);
            $filter->between('created_at', '创建时间')->datetime();
        });

        // 列配置
        $grid->column('id', 'ID')->sortable();
        $grid->column('event_id', '事件ID')->copyable();
        $grid->column('type', '事件类型')->display(function ($type) {
            $types = [
                'charge.complete' => '支付完成',
                'charge.failed' => '支付失败',
                'refund.created' => '退款创建',
            ];
            return $types[$type] ?? $type;
        })->label('info');
        
        $grid->column('process_status', '处理状态')->display(function ($status) {
            $model = new WebhookEvent();
            $model->process_status = $status;
            return "<span class='label label-{$model->status_color}'>{$model->status_label}</span>";
        });
        
        $grid->column('event_created_at', '事件时间')->sortable();
        $grid->column('processed_at', '处理时间')->sortable();
        $grid->column('created_at', '创建时间')->sortable();
        
        // 操作列
        $grid->actions(function ($actions) {
            $actions->disableDelete(); // 禁用删除
            $actions->disableEdit();   // 禁用编辑（只允许查看）
        });

        // 批量操作
        $grid->batchActions(function ($batch) {
            $batch->disableDelete(); // 禁用批量删除
        });

        // 设置每页显示数量
        $grid->paginate(20);

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
        $show = new Show(WebhookEvent::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('event_id', '事件ID');
        $show->field('type', '事件类型')->as(function ($type) {
            $types = [
                'charge.complete' => '支付完成',
                'charge.failed' => '支付失败',
                'refund.created' => '退款创建',
            ];
            return $types[$type] ?? $type;
        });
        
        $show->field('process_status', '处理状态')->as(function ($status) {
            $model = new WebhookEvent();
            $model->process_status = $status;
            return "<span class='label label-{$model->status_color}'>{$model->status_label}</span>";
        });
        
        $show->field('event_created_at', '事件时间');
        $show->field('processed_at', '处理时间');
        $show->field('error_message', '错误信息')->as(function ($message) {
            return $message ?: '无';
        });
        
        $show->field('payload', '事件载荷')->json();
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
        $form = new Form(new WebhookEvent());

        $form->display('id', 'ID');
        $form->text('event_id', '事件ID')->required();
        $form->select('type', '事件类型')->options([
            'charge.complete' => '支付完成',
            'charge.failed' => '支付失败',
            'refund.created' => '退款创建',
        ])->required();
        
        $form->select('process_status', '处理状态')->options([
            WebhookEvent::STATUS_PENDING => '待处理',
            WebhookEvent::STATUS_PROCESSED => '已处理',
            WebhookEvent::STATUS_FAILED => '处理失败',
        ])->required();
        
        $form->datetime('event_created_at', '事件时间');
        $form->datetime('processed_at', '处理时间');
        $form->textarea('error_message', '错误信息');
        $form->json('payload', '事件载荷');

        return $form;
    }

    /**
     * 重写编辑方法，允许编辑
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description('编辑')
            ->body($this->form()->edit($id));
    }

    /**
     * 重写更新方法
     */
    public function update($id, Request $request)
    {
        $webhookEvent = WebhookEvent::findOrFail($id);
        
        // 更新数据
        $webhookEvent->update($request->all());
        
        admin_success('更新成功！');
        
        return redirect()->back();
    }
}
