<?php

use App\Enums\CustomerStatus;
use App\Enums\CustomerType;
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable()->unique();
            $table->string('tax_number')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('address')->nullable();
            $table->text('note')->nullable();
            $table->enum('type', CustomerType::cases())->default(CustomerType::Individual);
            $table->enum('status', CustomerStatus::cases())->default(CustomerStatus::Active);
            $table->timestamps();

            $table->index(['type', 'status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
