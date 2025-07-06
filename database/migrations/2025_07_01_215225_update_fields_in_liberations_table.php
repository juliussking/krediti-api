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
        Schema::table('liberations', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->required()->change();
            $table->date('expiration_date')->required()->after('status');
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete()->after('expiration_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liberations', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->nullable()->change();
            $table->dropColumn('expiration_date');
            $table->dropColumn('company_id');
        });
    }
};
