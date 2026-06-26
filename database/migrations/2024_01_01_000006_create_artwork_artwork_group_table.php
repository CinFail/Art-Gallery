<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot: one artwork can belong to many groups
        Schema::create('artwork_artwork_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_id')->constrained()->cascadeOnDelete();
            $table->foreignId('artwork_group_id')->constrained()->cascadeOnDelete();
            $table->unique(['artwork_id', 'artwork_group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artwork_artwork_group');
    }
};
