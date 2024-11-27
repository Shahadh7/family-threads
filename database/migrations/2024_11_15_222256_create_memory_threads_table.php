<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memory_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memory_item_id')->constrained('memory_items')->onDelete('cascade'); // Link to memory items
            $table->string('location')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memory_threads');
    }
};
