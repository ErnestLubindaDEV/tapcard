<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_analytics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workspace_id')
                ->constrained('workspaces')
                ->cascadeOnDelete();

            $table->foreignId('business_card_id')
                ->constrained('business_cards')
                ->cascadeOnDelete();

            $table->string('event_type');

            $table->string('event_source')->nullable();

            $table->text('referrer')->nullable();

            // Store a hash, not the visitor's raw IP address.
            $table->string('ip_hash', 64)->nullable();

            $table->text('user_agent')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamp('occurred_at')->useCurrent();

            $table->timestamps();

            $table->index(['business_card_id', 'event_type']);
            $table->index(['workspace_id', 'event_type']);
            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_analytics');
    }
};