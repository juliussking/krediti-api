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
        Schema::create('solicitations', function (Blueprint $table) {
            $table->id();

            // Relacionamentos
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();

            // Dados da solicitação
            $table->decimal('amount_requested', 10, 2); // obrigatório
            $table->decimal('counteroffer', 10, 2)->nullable();
            $table->decimal('amount_approved', 10, 2)->nullable();
            $table->float('tax'); // obrigatório
            $table->decimal('total', 10, 2); // obrigatório
            $table->string('status')->default('Pendente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitations');
    }
};
