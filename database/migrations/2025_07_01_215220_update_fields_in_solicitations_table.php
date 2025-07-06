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
        Schema::table('solicitations', function (Blueprint $table) {
            $table->decimal('amount_requested', 10, 2)->required()->change();
            $table->decimal('counteroffer', 10, 2)->nullable()->after('amount_requested');
            $table->float('tax')->required()->change();
            $table->decimal('total', 10, 2)->required()->after('tax');
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitations', function (Blueprint $table) {
            $table->decimal('amount_requested', 10, 2)->nullable()->change();
            $table->float('tax')->nullable()->change();
            $table->dropColumn('counteroffer');
            $table->dropColumn('total');
            $table->dropColumn('company_id');
        });
    }
};
