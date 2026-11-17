
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
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Public profile URL
            $table->string('slug')->unique();

            // Personal information
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('job_title')->nullable();

            $table->string('company_name')->nullable();
            $table->text('bio')->nullable();

            $table->string('profile_photo')->nullable();
            $table->string('cover_photo')->nullable();

            // Contact information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('website')->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();

            // Theme reference (foreign key added separately)
            $table->unsignedBigInteger('card_theme_id')->nullable();

            // draft, published, archived
            $table->string('status')->default('draft');

            // public, unlisted, private
            $table->string('visibility')->default('public');

            $table->boolean('is_primary')->default(false);
            $table->boolean('show_email')->default(true);
            $table->boolean('show_phone')->default(true);

            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['workspace_id', 'status']);
            $table->index(['workspace_id', 'user_id']);
            $table->index(['workspace_id', 'visibility']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_cards');
    }
};
