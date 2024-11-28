<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_capsules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memory_item_id')->constrained('memory_items')->onDelete('cascade'); // Link to memory items
            $table->timestamp('open_date');
            $table->enum('status', ['Locked', 'Opened'])->default('Locked');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_capsules');
    }
};
