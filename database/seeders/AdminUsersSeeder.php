<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 先删除现有数据
        DB::table('admin_users')->truncate();
        $this->command->info('🗑️  已清空现有管理员数据');
        
        // 创建默认管理员用户
        DB::table('admin_users')->insert([
            'username' => 'admin',
            'password' => Hash::make('123456'),
            'name' => 'Administrator',
            'avatar' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ 默认管理员用户已创建');
        $this->command->info('   用户名: admin');
        $this->command->info('   密码: 123456');

       }
}
