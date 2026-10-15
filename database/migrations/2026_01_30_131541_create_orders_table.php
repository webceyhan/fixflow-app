<?php

use App\Enums\OrderStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('supplier')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('cost')->default(0);
            $table->boolean('is_billable')->default(true);
            $table->date('due_date')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', OrderStatus::cases())->default(OrderStatus::Created);
            $table->timestamps();
            $table->timestamp('approved_at')->nullable();

            $table->index(['assignee_id', 'status', 'created_at']);
            $table->index(['status', 'due_date', 'created_at']);
            $table->index('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
