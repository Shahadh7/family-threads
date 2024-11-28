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
        Schema::create('files', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // File name
            $table->string('path'); // Path to the file in DigitalOcean Spaces
            $table->string('url'); // Full URL for accessing the file
            $table->string('mime_type')->nullable(); // File MIME type
            $table->unsignedBigInteger('size')->nullable(); // File size in bytes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
