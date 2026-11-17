<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_cards', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workspace_id')
                ->constrained('workspaces')
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('slug')->unique();

            // Personal information
            $table->string('first_name');
            $table->string('last_name')->nullable();

            // Professional information
            $table->string('job_title')->nullable();
            $table->string('company_name')->nullable();
            $table->text('bio')->nullable();

            // Images
            $table->string('profile_photo')->nullable();
            $table->string('cover_photo')->nullable();

            // Contact information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();

            // Online presence
            $table->string('website')->nullable();

            // Location
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();

            // Theme reference: foreign key added in a later migration
            $table->unsignedBigInteger('card_theme_id')->nullable();

            // Card settings
            $table->string('status')->default('draft');
            $table->string('visibility')->default('public');

            $table->boolean('is_primary')->default(false);

            $table->boolean('show_email')->default(true);
            $table->boolean('show_phone')->default(true);

            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['workspace_id', 'status']);
            $table->index(['workspace_id', 'visibility']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_cards');
    }
};