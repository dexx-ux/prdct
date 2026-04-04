<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  

 public function up(): void
{
    Schema::table('products', function (Blueprint $table) {
        // single optional value; string so we can include a trailing "%" for percentages
        $table->string('discount_value')
              ->nullable()
              ->after('price');
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['discount_value']);
    });
}
};