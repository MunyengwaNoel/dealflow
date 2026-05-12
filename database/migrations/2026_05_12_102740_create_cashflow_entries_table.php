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
        Schema::create('cashflow_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');

            $table->enum('entry_type', ['income', 'expense']);
            $table->string('category');
            $table->longText('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'ecocash', 'zipit', 'bank_transfer', 'other']);
            $table->date('entry_date');
            $table->string('reference')->nullable();

            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices');
            $table->foreignId('recorded_by')->nullable()->constrained('users');

            $table->timestamps();

            $table->index(['tenant_id', 'entry_type']);
            $table->index(['tenant_id', 'entry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashflow_entries');
    }
};
