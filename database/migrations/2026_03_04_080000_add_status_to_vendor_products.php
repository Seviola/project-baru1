<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('stock');
            $table->integer('approved_stock')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_stock']);
        });
    }
};
