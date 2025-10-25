<div class="row">
    <!-- 教师统计 -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $teacherCount }}</h3>
                <p>教师总数</p>
            </div>
            <div class="icon">
                <i class="fa fa-user-tie"></i>
            </div>
            <a href="/admin/teachers" class="small-box-footer">
                更多信息 <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- 学生统计 -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $studentCount }}</h3>
                <p>学生总数</p>
            </div>
            <div class="icon">
                <i class="fa fa-user-graduate"></i>
            </div>
            <a href="/admin/students" class="small-box-footer">
                更多信息 <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- 课程统计 -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $courseCount }}</h3>
                <p>课程总数</p>
            </div>
            <div class="icon">
                <i class="fa fa-book"></i>
            </div>
            <a href="/admin/courses" class="small-box-footer">
                更多信息 <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- 账单统计 -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $invoiceCount }}</h3>
                <p>账单总数</p>
            </div>
            <div class="icon">
                <i class="fa fa-file-invoice"></i>
            </div>
            <a href="/admin/invoices" class="small-box-footer">
                更多信息 <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- 详细统计信息 -->
<div class="row">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">用户状态统计</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-user-tie"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">活跃教师</span>
                                <span class="info-box-number">{{ $activeTeacherCount }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-user-graduate"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">活跃学生</span>
                                <span class="info-box-number">{{ $activeStudentCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">账单状态统计</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">待处理</span>
                                <span class="info-box-number">{{ $pendingInvoices }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">已支付</span>
                                <span class="info-box-number">{{ $paidInvoices }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-blue"><i class="fa fa-money"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">总收入</span>
                                <span class="info-box-number">¥{{ number_format($totalAmount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 快速操作 -->
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">快速操作</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="/admin/students/create" class="btn btn-success btn-block">
                            <i class="fa fa-plus"></i> 添加学生
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/admin/courses/create" class="btn btn-warning btn-block">
                            <i class="fa fa-plus"></i> 创建课程
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
