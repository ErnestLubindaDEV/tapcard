
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_social_links', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_card_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('platform');
            $table->string('label')->nullable();
            $table->text('url');

            $table->string('icon')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->boolean('is_visible')->default(true);

            $table->timestamps();

            $table->index([
                'business_card_id',
                'sort_order'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_social_links');
    }
};
