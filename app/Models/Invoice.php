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
        'omise_charge_id',
        'paid_at',
        'payment_method',
        'currency',
        'payment_success',
        'payment_status',
        'payment_transaction_id',
        'payment_error_message',
        'payment_processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_success' => 'boolean',
        'payment_processed_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_paid_at',
        'formatted_created_at',
        'formatted_payment_processed_at',
        'formatted_updated_at'
    ];

    /**
     * 账单状态常量
     */
    const STATUS_PENDING = 0;      // 待支付
    const STATUS_PROCESSING = 1;    // 支付中
    const STATUS_PAID = 2;         // 支付成功
    const STATUS_FAILED = 3;       // 支付失败

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
            self::STATUS_PENDING => '待支付',
            self::STATUS_PROCESSING => '支付中',
            self::STATUS_PAID => '支付成功',
            self::STATUS_FAILED => '支付失败',
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
        return $this->status === self::STATUS_PENDING;
    }
    
    /**
     * 判断是否正在支付中
     */
    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }
    
    /**
     * 判断是否支付失败
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * 获取格式化的支付时间
     */
    public function getFormattedPaidAtAttribute()
    {
        return $this->paid_at ? $this->paid_at->format('Y-m-d H:i:s') : null;
    }

    /**
     * 获取格式化的创建时间
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
    }

    /**
     * 获取格式化的支付处理时间
     */
    public function getFormattedPaymentProcessedAtAttribute()
    {
        return $this->payment_processed_at ? $this->payment_processed_at->format('Y-m-d H:i:s') : null;
    }

    /**
     * 获取格式化的更新时间
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null;
    }
}
