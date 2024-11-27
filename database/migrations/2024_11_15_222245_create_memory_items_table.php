<?php

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
        Schema::create('memory_items', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('added_by_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('family_id')->constrained('families')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamp('date_added')->useCurrent();
            $table->enum('type', ['MemoryThread', 'TimeCapsule', 'Keepsake']);
            $table->foreignId('file_id')->nullable()->constrained('files')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memory_items');
    }
};
