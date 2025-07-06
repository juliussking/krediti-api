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
        Schema::table('client_profiles', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('marital_status')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_profiles', function (Blueprint $table) {
            $table->string('birth_date')->change();
            $table->string('phone')->change();
            $table->string('marital_status')->change();
        });
    }
};
