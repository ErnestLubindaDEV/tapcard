<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_themes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workspace_id')
                ->nullable()
                ->constrained('workspaces')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('slug');

            $table->text('description')->nullable();

            $table->string('preview_image')->nullable();

            $table->json('colors')->nullable();
            $table->json('settings')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);

            $table->timestamps();

            $table->unique(['workspace_id', 'slug']);

            $table->index(['workspace_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_themes');
    }
};