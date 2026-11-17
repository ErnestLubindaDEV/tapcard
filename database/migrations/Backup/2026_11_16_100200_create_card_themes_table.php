
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

            // Null means globally available theme
            $table->foreignId('workspace_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('slug');

            $table->string('description')->nullable();

            $table->string('preview_image')->nullable();

            $table->json('colors')->nullable();
            $table->json('settings')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);

            $table->timestamps();

            $table->unique(
                ['workspace_id', 'slug'],
                'workspace_theme_slug_unique'
            );

            $table->index(['workspace_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_themes');
    }
};
