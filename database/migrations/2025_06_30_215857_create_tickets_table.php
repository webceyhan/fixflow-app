<?php

use App\Enums\Priority;
use App\Enums\TicketStatus;
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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('priority', Priority::values())->default(Priority::Medium);
            $table->enum('status', TicketStatus::values())->default(TicketStatus::New);
            $table->date('due_date')->nullable();
            $table->timestamps();

            // computed task counters
            $table->integer('pending_tasks_count')->default(0);
            $table->integer('complete_tasks_count')->default(0);
            $table->integer('total_tasks_count')->default(0);

            // computed order counters
            $table->integer('pending_orders_count')->default(0);
            $table->integer('complete_orders_count')->default(0);
            $table->integer('total_orders_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
