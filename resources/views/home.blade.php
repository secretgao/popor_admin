<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统信息 - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 40px;
        }
        .section h2 {
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .info-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 20px;
        }
        .info-card h3 {
            margin-top: 0;
            color: #495057;
            font-size: 1.2em;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        .info-value {
            color: #495057;
            word-break: break-all;
        }
        .status-success {
            color: #28a745;
            font-weight: bold;
        }
        .status-error {
            color: #dc3545;
            font-weight: bold;
        }
        .table-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #e9ecef;
            border-radius: 4px;
        }
        .table-item {
            padding: 8px 12px;
            border-bottom: 1px solid #e9ecef;
            font-family: monospace;
        }
        .table-item:last-child {
            border-bottom: none;
        }
        .error-box {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .back-link:hover {
            background: #5a6fd8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>系统信息面板</h1>
            <p>数据库连接状态 & 环境配置信息</p>
        </div>

        <div class="content">
            <a href="{{ url('/admin') }}" class="back-link">进入管理后台</a>

            @if(isset($error))
                <div class="error-box">
                    <strong>数据库连接错误:</strong> {{ $error }}
                </div>
            @endif

            <div class="info-grid">
                <!-- 环境信息 -->
                <div class="info-card">
                    <h3>🌍 环境信息</h3>
                    @foreach($envInfo as $key => $value)
                        <div class="info-item">
                            <span class="info-label">{{ $key }}:</span>
                            <span class="info-value">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- 数据库配置 -->
                <div class="info-card">
                    <h3>🗄️ 数据库配置</h3>
                    @if(isset($dbConfig))
                        @foreach($dbConfig as $key => $value)
                            @if(!in_array($key, ['password', 'options']))
                                <div class="info-item">
                                    <span class="info-label">{{ $key }}:</span>
                                    <span class="info-value">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                <!-- 系统信息 -->
                <div class="info-card">
                    <h3>⚙️ 系统信息</h3>
                    @foreach($systemInfo as $key => $value)
                        <div class="info-item">
                            <span class="info-label">{{ $key }}:</span>
                            <span class="info-value">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- 数据库状态 -->
                <div class="info-card">
                    <h3>📊 数据库状态</h3>
                    <div class="info-item">
                        <span class="info-label">连接状态:</span>
                        <span class="info-value {{ isset($dbStatus) && $dbStatus === 'Connected' ? 'status-success' : 'status-error' }}">
                            {{ $dbStatus ?? 'Failed' }}
                        </span>
                    </div>
                    @if(isset($dbVersion))
                        <div class="info-item">
                            <span class="info-label">数据库版本:</span>
                            <span class="info-value">{{ $dbVersion }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 数据库表列表 -->
            @if(isset($tables) && count($tables) > 0)
                <div class="section">
                    <h2>📋 数据库表列表 ({{ count($tables) }} 个表)</h2>
                    <div class="table-list">
                        @foreach($tables as $table)
                            <div class="table-item">{{ $table->table_name }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- 管理员用户列表 -->
            @if(isset($adminUsers) && $adminUsers->count() > 0)
                <div class="section">
                    <h2>👥 管理员用户列表 ({{ $adminUsers->count() }} 个用户)</h2>
                    <div class="info-grid">
                        @foreach($adminUsers as $user)
                            <div class="info-card">
                                <h3>👤 {{ $user->name }}</h3>
                                <div class="info-item">
                                    <span class="info-label">ID:</span>
                                    <span class="info-value">{{ $user->id }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">用户名:</span>
                                    <span class="info-value"><strong>{{ $user->username }}</strong></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">邮箱:</span>
                                    <span class="info-value">{{ $user->email ?: '未设置' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">状态:</span>
                                    <span class="info-value {{ $user->is_active ? 'status-success' : 'status-error' }}">
                                        {{ $user->is_active ? '活跃' : '禁用' }}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">创建时间:</span>
                                    <span class="info-value">{{ $user->created_at->format('Y-m-d H:i:s') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">更新时间:</span>
                                    <span class="info-value">{{ $user->updated_at->format('Y-m-d H:i:s') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
