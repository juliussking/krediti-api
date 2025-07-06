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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_type')->required()->change();
            $table->decimal('amount', 10, 2)->required()->change();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_type')->change();
            $table->decimal('amount', 10, 2)->change();
            $table->dropColumn('company_id');
        });
    }
};
