
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
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('business_card_id')
                ->constrained()
                ->cascadeOnDelete();

            // view, qr_scan, contact_save, link_click, share
            $table->string('event_type');

            $table->string('event_source')->nullable();

            $table->string('referrer')->nullable();

            // Store hashed IP, not raw IP address
            $table->string('ip_hash', 64)->nullable();

            $table->text('user_agent')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamp('occurred_at')->useCurrent();

            $table->timestamps();

            $table->index(
                ['business_card_id', 'event_type', 'occurred_at'],
                'card_event_time_index'
            );

            $table->index(
                ['workspace_id', 'occurred_at'],
                'workspace_event_time_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_analytics');
    }
};
