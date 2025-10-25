<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    protected $fillable = [
        'course_id',
        'student_id',
        'year_month',
        'amount',
        'status',
        'sent_at',
        'omise_charge_id',
        'omise_source_id',
        'omise_last_event_id',
        'paid_at',
        'payment_method',
        'currency',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * 账单状态常量
     */
    const STATUS_PENDING = 0;
    const STATUS_SENT = 1;
    const STATUS_PAID = 2;
    const STATUS_CANCELLED = 3;

    /**
     * 关联课程
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * 关联学生
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * 获取状态名称
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => '待处理',
            self::STATUS_SENT => '已发送',
            self::STATUS_PAID => '已支付',
            self::STATUS_CANCELLED => '已取消',
            default => '未知状态',
        };
    }

    /**
     * 判断是否已支付
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * 判断是否可以取消
     */
    public function canCancel(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_SENT]);
    }
}
