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
        Schema::create('liberation_backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('liberation_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount',10,2)->nullable();
            $table->string('status')->nullable();
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liberation_backups');
    }
};
