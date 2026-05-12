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
        Schema::create('deal_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users');

            $table->enum('activity_type', ['note', 'call', 'email', 'meeting', 'whatsapp', 'task']);
            $table->longText('description');
            $table->date('activity_date')->nullable();

            $table->timestamps();

            $table->index(['deal_id', 'activity_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_activities');
    }
};
