<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW orders AS
            SELECT
                'user' as order_type,
                uo.id,
                uo.user_id,
                u.name as customer_name,
                u.email as customer_email,
                NULL as customer_phone,
                uo.total_amount as total,
                uo.status,
                uo.created_at,
                uo.updated_at
            FROM user_orders uo
            LEFT JOIN users u ON uo.user_id = u.id
            UNION ALL
            SELECT
                'guest' as order_type,
                go.id,
                NULL as user_id,
                go.customer_name,
                go.customer_email,
                go.customer_phone,
                go.total_amount as total,
                go.status,
                go.created_at,
                go.updated_at
            FROM guest_orders go
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS orders");
    }
};
