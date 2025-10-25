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
        
        .table-structure {
            margin-bottom: 2rem;
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .table-structure h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
            border-bottom: 2px solid #3498db;
            padding-bottom: 0.5rem;
        }
        
        .structure-table {
            overflow-x: auto;
        }
        
        .structure-table table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .structure-table th {
            background-color: #3498db;
            color: white;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
        }
        
        .structure-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
        }
        
        .structure-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .structure-table tr:hover {
            background-color: #e3f2fd;
        }
        
        .data-table {
            overflow-x: auto;
            margin-top: 1rem;
        }
        
        .data-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            background-color: #2c3e50;
            color: white;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
        }
        
        .data-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .data-table tr:hover {
            background-color: #e3f2fd;
        }
        
        .data-table .status-success {
            color: #28a745;
            font-weight: 600;
        }
        
        .data-table .status-error {
            color: #dc3545;
            font-weight: 600;
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
            
            <!-- admin_users 表数据 -->
            <div class="section">
                <h2>📊 admin_users 表数据 ({{ isset($adminUsersData) ? $adminUsersData->count() : 0 }} 条记录)</h2>
                <div class="data-table">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>用户名</th>
                                <th>姓名</th>
                                <th>头像</th>
                                <th>状态</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($adminUsersData) && $adminUsersData->count() > 0)
                                @foreach($adminUsersData as $user)
                                    <tr>
                                        <td><strong>{{ $user->id }}</strong></td>
                                        <td>{{ $user->username ?: '未设置' }}</td>
                                        <td>{{ $user->name ?: '未设置' }}</td>
                                        <td>{{ $user->avatar ?: '未设置' }}</td>
                                        <td>
                                            <span class="{{ $user->is_active ? 'status-success' : 'status-error' }}">
                                                {{ $user->is_active ? '活跃' : '禁用' }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('Y-m-d H:i:s') : '未知' }}</td>
                                        <td>{{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('Y-m-d H:i:s') : '未知' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #666; padding: 2rem;">
                                        暂无数据
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- 表结构信息 -->
            @if(isset($tableStructures) && count($tableStructures) > 0)
                <div class="section">
                    <h2>🗂️ 重要表结构信息</h2>
                    @foreach($tableStructures as $tableName => $columns)
                        @if(count($columns) > 0)
                            <div class="table-structure">
                                <h3>📋 {{ $tableName }} 表结构</h3>
                                <div class="structure-table">
                                    <table class="users-table">
                                        <thead>
                                            <tr>
                                                <th>字段名</th>
                                                <th>数据类型</th>
                                                <th>可空</th>
                                                <th>默认值</th>
                                                <th>最大长度</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($columns as $column)
                                                <tr>
                                                    <td><strong>{{ $column->column_name }}</strong></td>
                                                    <td>{{ $column->data_type }}</td>
                                                    <td>{{ $column->is_nullable === 'YES' ? '是' : '否' }}</td>
                                                    <td>{{ $column->column_default ?: '无' }}</td>
                                                    <td>{{ $column->character_maximum_length ?: '无限制' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
