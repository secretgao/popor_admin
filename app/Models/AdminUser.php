<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;

class AdminUser extends Authenticatable
{
    use HasFactory, Notifiable, AdminBuilder, ModelTree;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'is_active',
        'is_del',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_del' => 'boolean',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    /**
     * 时间格式
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 教师创建的课程
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    /**
     * 获取角色关系
     */
    public function roles()
    {
        return $this->belongsToMany(
            \Encore\Admin\Auth\Database\Role::class,
            'admin_role_users',
            'user_id',
            'role_id'
        );
    }

    /**
     * 获取权限关系
     */
    public function permissions()
    {
        return $this->belongsToMany(
            \Encore\Admin\Auth\Database\Permission::class,
            'admin_user_permissions',
            'user_id',
            'permission_id'
        );
    }

    /**
     * 获取格式化的创建时间
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : '未知';
    }

    /**
     * 获取格式化的更新时间
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : '未知';
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
