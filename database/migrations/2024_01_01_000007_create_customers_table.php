<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('address');
            $table->decimal('total_spent', 14, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Preferred artists (many-to-many)
        Schema::create('customer_preferred_artists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
            $table->unique(['customer_id', 'artist_id']);
        });

        // Preferred artwork groups (many-to-many)
        Schema::create('customer_preferred_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('artwork_group_id')->constrained()->cascadeOnDelete();
            $table->unique(['customer_id', 'artwork_group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_preferred_groups');
        Schema::dropIfExists('customer_preferred_artists');
        Schema::dropIfExists('customers');
    }
};
