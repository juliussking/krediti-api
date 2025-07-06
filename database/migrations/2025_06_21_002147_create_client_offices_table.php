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
        Schema::create('client_offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('neighbor')->nullable();
            $table->integer('number')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('role')->nullable();
            $table->integer('salary')->nullable();
            $table->date('payment_date')->nullable();
            $table->date('admission_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_offices');
    }
};
