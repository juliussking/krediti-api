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
        Schema::table('client_offices', function (Blueprint $table) {
            $table->decimal('salary', 10, 2)->required()->change();
            $table->string('role')->required()->change();
            $table->date('payment_date')->required()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_offices', function (Blueprint $table) {
            $table->integer('salary')->nullable()->change();
            $table->string('role')->nullable()->change();
            $table->date('payment_date')->nullable()->change();
        });
    }
};
