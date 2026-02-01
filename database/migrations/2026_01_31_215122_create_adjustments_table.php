<?php

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
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
        Schema::create('adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount')->default(0);
            $table->boolean('is_percent')->default(false);
            $table->string('note')->nullable();
            $table->enum('type', AdjustmentType::cases())->default(AdjustmentType::Discount);
            $table->enum('reason', AdjustmentReason::cases())->default(AdjustmentReason::Welcome);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustments');
    }
};
