<?php

namespace App\Admin\Controllers;

use App\Models\WebhookEvent;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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

        // 列配置 - 显示所有字段
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
        
        $grid->column('payload', '事件载荷')->display(function ($payload) {
            if (is_array($payload)) {
                return '<pre style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">' . 
                       htmlspecialchars(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . 
                       '</pre>';
            }
            return $payload;
        });
        
        $grid->column('process_status', '处理状态')->display(function ($status) {
            $model = new WebhookEvent();
            $model->process_status = $status;
            return "<span class='label label-{$model->status_color}'>{$model->status_label}</span>";
        });
        
        $grid->column('event_created_at', '事件时间')->display(function () {
            try {
                return $this->formatted_event_created_at ?: '未知';
            } catch (\Exception $e) {
                return '未知';
            }
        })->sortable();
        
        $grid->column('processed_at', '处理时间')->display(function () {
            try {
                return $this->formatted_processed_at ?: '未知';
            } catch (\Exception $e) {
                return '未知';
            }
        })->sortable();
        
        $grid->column('error_message', '错误信息')->display(function ($message) {
            return $message ?: '无';
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
        
        // 禁用创建按钮--只能通过系统自动创建
        $grid->disableCreateButton();
        
        // 禁用所有操作列
        $grid->disableActions();

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

}
