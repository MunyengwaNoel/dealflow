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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('client_id')->constrained('clients');
            $table->unsignedBigInteger('quote_id')->nullable();

            $table->string('invoice_number')->unique();
            $table->enum('status', ['draft', 'sent', 'partial', 'paid', 'overdue', 'cancelled'])->default('draft');

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('amount_due', 10, 2)->default(0);

            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();

            $table->enum('payment_method', ['cash', 'ecocash', 'zipit', 'bank_transfer', 'other'])->nullable();
            $table->longText('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users');

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'client_id']);
            $table->index(['tenant_id', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
