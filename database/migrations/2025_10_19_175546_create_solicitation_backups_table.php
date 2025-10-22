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
        Schema::create('solicitation_backups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('solicitation_id')->constrained('solicitations')->cascadeOnDelete();
            $table->decimal('amount_requested', 10, 2)->nullable();
            $table->decimal('counteroffer', 10, 2)->nullable();
            $table->decimal('amount_approved', 10, 2)->nullable();
            $table->float('tax')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitation_backups');
    }
};
