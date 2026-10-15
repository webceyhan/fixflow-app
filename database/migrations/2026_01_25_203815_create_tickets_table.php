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
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('note')->nullable();
            $table->enum('priority', Priority::cases())->default(Priority::Medium);
            $table->enum('status', TicketStatus::cases())->default(TicketStatus::Created);
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->index(['assignee_id', 'status', 'created_at']);
            $table->index(['priority', 'status', 'created_at']);
            $table->index('due_date');
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
