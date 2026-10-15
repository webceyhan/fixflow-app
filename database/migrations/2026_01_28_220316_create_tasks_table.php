<?php

use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\TaskType;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('cost')->default(0);
            $table->boolean('is_billable')->default(true);
            $table->text('note')->nullable();
            $table->enum('priority', Priority::cases())->default(Priority::Medium);
            $table->enum('type', TaskType::cases())->default(TaskType::Repair);
            $table->enum('status', TaskStatus::cases())->default(TaskStatus::Created);
            $table->timestamps();
            $table->timestamp('approved_at')->nullable();

            $table->index(['assignee_id', 'status', 'created_at']);
            $table->index(['type', 'priority', 'status', 'created_at']);
            $table->index('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
