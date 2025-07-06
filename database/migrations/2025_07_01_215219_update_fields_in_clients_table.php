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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('name')->required()->change();
            $table->string('status')->default('Novo')->change();
            $table->decimal('debit', 10, 2)->default(0)->after('person_type');
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete()->after('status');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('status')->nullable()->change();
            $table->dropColumn('debit')->default(0);
            $table->dropColumn('company_id')->nullable();
        });
    }
};
