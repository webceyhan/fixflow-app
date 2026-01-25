<?php

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('model');
            $table->string('brand');
            $table->string('color')->nullable();
            $table->string('imei')->nullable()->unique();
            $table->string('serial_number')->nullable()->unique();
            $table->string('passcode')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expire_date')->nullable();
            $table->text('note')->nullable();
            $table->enum('type', DeviceType::cases())->default(DeviceType::Other);
            $table->enum('status', DeviceStatus::cases())->default(DeviceStatus::Received);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
