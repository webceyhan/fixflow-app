<?php

use App\Enums\InvoiceStatus;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->decimal('total')->default(0);
            $table->date('due_date');
            $table->enum('status', InvoiceStatus::values())->default(InvoiceStatus::Draft);
            $table->timestamps();

            // computed cost totals
            $table->decimal('task_total')->default(0);
            $table->decimal('order_total')->default(0);

            // computed adjustment amounts
            $table->decimal('discount_amount')->default(0);
            $table->decimal('fee_amount')->default(0);
            $table->decimal('compensation_amount')->default(0);
            $table->decimal('bonus_amount')->default(0);

            // computed transaction amounts
            $table->decimal('paid_amount')->default(0);
            $table->decimal('refunded_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
