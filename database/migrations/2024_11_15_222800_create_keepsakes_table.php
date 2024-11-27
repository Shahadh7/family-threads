<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keepsakes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memory_item_id')->constrained('memory_items')->onDelete('cascade'); // Link to memory items
            $table->foreignId('given_to_user_id')->nullable()->constrained('users')->onDelete('set null'); // Currently assigned user
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keepsakes');
    }
};
