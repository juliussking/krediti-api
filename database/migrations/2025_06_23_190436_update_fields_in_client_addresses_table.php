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
        Schema::table('client_addresses', function (Blueprint $table) {
            $table->string('street')->required()->change();
            $table->string('city')->nullable()->change();
            $table->string('neighbor')->nullable()->change();
            $table->integer('number')->nullable()->change();
            $table->string('reference_point')->required()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_addresses', function (Blueprint $table) {
            $table->string('street')->nullable()->change();
            $table->string('city')->change();
            $table->string('neighbor')->change();
            $table->integer('number')->change();
            $table->string('reference_point')->nullable()->change();
        });
    }
};
