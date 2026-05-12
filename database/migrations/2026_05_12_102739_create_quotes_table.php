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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('client_id')->constrained('clients');

            $table->string('quote_number')->unique();
            $table->enum('status', ['draft', 'sent', 'accepted', 'declined', 'invoiced'])->default('draft');

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('profit_total', 10, 2)->default(0);

            $table->longText('notes')->nullable();
            $table->date('valid_until')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users');

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
