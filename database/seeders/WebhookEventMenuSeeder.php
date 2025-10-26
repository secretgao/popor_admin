<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Role;

class WebhookEventMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建 Webhook 事件菜单
        $menu = Menu::create([
            'parent_id' => 0,
            'order' => 100,
            'title' => 'Webhook 事件',
            'icon' => 'fa-bell',
            'uri' => 'webhook-events',
            'permission' => '*',
        ]);

        // 获取管理员角色
        $adminRole = Role::where('slug', 'administrator')->first();
        
        if ($adminRole) {
            // 将菜单分配给管理员角色
            $menu->roles()->attach($adminRole->id);
            $this->command->info('Webhook 事件菜单已创建并分配给管理员角色');
        } else {
            $this->command->warn('未找到管理员角色，菜单已创建但未分配权限');
        }
    }
}
