<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 更新 invoices 表的 status 字段注释，新增"待发送"状态
        DB::statement("COMMENT ON COLUMN invoices.status IS '账单状态：0=待发送, 1=待支付, 2=支付中, 3=支付成功, 4=支付失败'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 恢复原来的注释
        DB::statement("COMMENT ON COLUMN invoices.status IS '账单状态：0=待支付, 1=支付中, 2=支付成功, 3=支付失败'");
    }
};