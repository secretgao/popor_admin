<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model
{
    protected $table = 'course_student';
    
    protected $fillable = [
        'course_id',
        'student_id',
    ];

    public $timestamps = false;
    
    protected $casts = [
        'created_at' => 'datetime',
    ];

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
}
