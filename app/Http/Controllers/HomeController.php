<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Encore\Admin\Auth\Database\Administrator;

class HomeController extends Controller
{
    /**
     * 显示数据库和环境信息
     */
    public function index()
    {
        try {
            // 获取数据库连接信息
            $dbConfig = Config::get('database.connections.pgsql');
            
            // 测试数据库连接
            $dbConnection = DB::connection()->getPdo();
            $dbStatus = 'Connected';
            
            // 获取数据库版本
            $dbVersion = DB::select('SELECT version() as version')[0]->version ?? 'Unknown';
            
            // 获取表列表
            $tables = DB::select("
                SELECT table_name 
                FROM information_schema.tables 
                WHERE table_schema = 'public' 
                ORDER BY table_name
            ");
            
            // 获取环境信息
            $envInfo = [
                'APP_NAME' => env('APP_NAME'),
                'APP_ENV' => env('APP_ENV'),
                'APP_DEBUG' => env('APP_DEBUG'),
                'APP_URL' => env('APP_URL'),
                'DB_CONNECTION' => env('DB_CONNECTION'),
                'DB_HOST' => env('DB_HOST'),
                'DB_PORT' => env('DB_PORT'),
                'DB_DATABASE' => env('DB_DATABASE'),
                'DB_USERNAME' => env('DB_USERNAME'),
                'DATABASE_URL' => env('DATABASE_URL') ? 'Set (hidden)' : 'Not set',
                'PHP_VERSION' => PHP_VERSION,
                'LARAVEL_VERSION' => app()->version(),
            ];
            
            // 获取系统信息
            $systemInfo = [
                'Server Time' => now()->format('Y-m-d H:i:s'),
                'Timezone' => config('app.timezone'),
                'Memory Usage' => $this->formatBytes(memory_get_usage(true)),
                'Peak Memory' => $this->formatBytes(memory_get_peak_usage(true)),
            ];
            
            // 获取管理员用户数据
            $adminUsers = Administrator::all();
            
            return view('home', compact(
                'dbConfig', 
                'dbStatus', 
                'dbVersion', 
                'tables', 
                'envInfo', 
                'systemInfo',
                'adminUsers'
            ));
            
        } catch (\Exception $e) {
            return view('home', [
                'error' => $e->getMessage(),
                'dbConfig' => Config::get('database.connections.pgsql'),
                'envInfo' => [
                    'APP_NAME' => env('APP_NAME'),
                    'APP_ENV' => env('APP_ENV'),
                    'APP_DEBUG' => env('APP_DEBUG'),
                    'APP_URL' => env('APP_URL'),
                    'DB_CONNECTION' => env('DB_CONNECTION'),
                    'DB_HOST' => env('DB_HOST'),
                    'DB_PORT' => env('DB_PORT'),
                    'DB_DATABASE' => env('DB_DATABASE'),
                    'DB_USERNAME' => env('DB_USERNAME'),
                    'DATABASE_URL' => env('DATABASE_URL') ? 'Set (hidden)' : 'Not set',
                    'PHP_VERSION' => PHP_VERSION,
                    'LARAVEL_VERSION' => app()->version(),
                ],
                'systemInfo' => [
                    'Server Time' => now()->format('Y-m-d H:i:s'),
                    'Timezone' => config('app.timezone'),
                    'Memory Usage' => $this->formatBytes(memory_get_usage(true)),
                    'Peak Memory' => $this->formatBytes(memory_get_peak_usage(true)),
                ],
                'adminUsers' => collect([])
            ]);
        }
    }
    
    /**
     * 格式化字节数
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
