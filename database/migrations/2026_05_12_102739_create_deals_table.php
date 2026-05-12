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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('client_id')->constrained('clients');

            $table->string('title');
            $table->longText('description')->nullable();
            $table->enum('stage', ['lead', 'follow_up', 'proposal', 'negotiation', 'won', 'lost'])->default('lead');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->decimal('value', 10, 2)->default(0);
            $table->date('expected_close_date')->nullable();
            $table->date('actual_close_date')->nullable();
            $table->string('lost_reason')->nullable();
            $table->longText('notes')->nullable();

            $table->foreignId('assigned_to')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'stage']);
            $table->index(['tenant_id', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
