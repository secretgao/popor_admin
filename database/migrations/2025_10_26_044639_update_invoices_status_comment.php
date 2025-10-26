<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 更新 invoices 表的 status 字段注释
        DB::statement("COMMENT ON COLUMN invoices.status IS '账单状态：0=待支付, 1=支付中, 2=支付成功, 3=支付失败'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 恢复原来的注释
        DB::statement("COMMENT ON COLUMN invoices.status IS '账单状态：0=待处理, 1=已发送, 2=已支付, 3=已取消'");
    }
};
