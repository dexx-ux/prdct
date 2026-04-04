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
        Schema::table('products', function (Blueprint $table) {
            // convert existing decimal column to string so that percentage values like "10%" are allowed
            $table->string('discount_value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Clear any non-numeric values before converting back to decimal
            DB::table('products')->whereNotNull('discount_value')->update(['discount_value' => null]);
            $table->decimal('discount_value', 10, 2)->nullable()->change();
        });
    }
};