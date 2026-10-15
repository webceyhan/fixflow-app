<?php

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount')->default(0);
            $table->text('note')->nullable();
            $table->enum('method', TransactionMethod::cases())->default(TransactionMethod::Cash);
            $table->enum('type', TransactionType::cases())->default(TransactionType::Payment);
            $table->timestamps();

            $table->index(['method', 'type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
