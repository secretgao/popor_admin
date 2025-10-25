<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    //use SoftDeletes;

    protected $fillable = [
        'name',
        'year_month',
        'fee',
        'teacher_id',
        'is_del',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'is_del' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * 时间格式
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 课程状态常量
     */
    const STATUS_PENDING = 0;
    const STATUS_SENT = 1;
    const STATUS_PAID = 2;
    const STATUS_CANCELLED = 3;

    /**
     * 授课教师
     */
    public function teacher()
    {
        return $this->belongsTo(AdminUser::class, 'teacher_id');
    }

    /**
     * 课程学生关系
     */
    public function courseStudents()
    {
        return $this->hasMany(CourseStudent::class);
    }

    /**
     * 课程的学生（通过中间表）
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'student_id');
    }

    /**
     * 课程的账单
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * 获取课程年月显示格式
     */
    public function getYearMonthDisplayAttribute(): string
    {
        if (strlen($this->year_month) === 6) {
            $year = substr($this->year_month, 0, 4);
            $month = substr($this->year_month, 4, 2);
            return $year . '年' . $month . '月';
        }
        return $this->year_month;
    }

    /**
     * 获取格式化的创建时间
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
    }

    /**
     * 获取格式化的更新时间
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null;
    }

    /**
     * 软删除scope
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('is_del', false);
    }

    /**
     * 软删除scope
     */
    public function scopeDeleted($query)
    {
        return $query->where('is_del', true);
    }

    /**
     * 软删除方法
     */
    public function softDelete()
    {
        $this->update(['is_del' => true]);
    }

    /**
     * 恢复软删除
     */
    public function restore()
    {
        $this->update(['is_del' => false]);
    }
}
