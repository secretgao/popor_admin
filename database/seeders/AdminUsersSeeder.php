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
        // 检查是否已存在管理员用户
        if (DB::table('admin_users')->count() == 0) {
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
        } else {
            $this->command->info('ℹ️  管理员用户已存在，跳过创建');
        }

        // 创建额外的测试用户（可选）
        $testUsers = [
            [
                'username' => 'manager',
                'password' => Hash::make('manager123'),
                'name' => 'Manager',
                'avatar' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'editor',
                'password' => Hash::make('editor123'),
                'name' => 'Editor',
                'avatar' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($testUsers as $user) {
            if (!DB::table('admin_users')->where('username', $user['username'])->exists()) {
                DB::table('admin_users')->insert($user);
                $this->command->info("✅ 测试用户 {$user['username']} 已创建");
            }
        }
    }
}
