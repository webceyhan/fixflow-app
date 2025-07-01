<?php

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
            $table->string('description');
            $table->decimal('cost')->default(0);
            $table->boolean('is_billable')->default(true);
            $table->enum('type', TaskType::values())->default(TaskType::Repair);
            $table->enum('status', TaskStatus::values())->default(TaskStatus::New);
            $table->timestamps();
            $table->timestamp('approved_at')->nullable();
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
