<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
                $table->string('order_number', 32);
                $table->string('status', 32)->default('draft');
                $table->json('wizard_state')->nullable();
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->decimal('total_cost', 12, 2)->default(0);
                $table->decimal('profit_amount', 12, 2)->default(0);
                $table->decimal('profit_margin', 5, 2)->nullable();
                $table->string('payment_terms', 32)->nullable();
                $table->decimal('deposit_amount', 12, 2)->nullable();
                $table->decimal('balance_amount', 12, 2)->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('quote_id')->nullable()->constrained('quotes')->nullOnDelete();
                $table->timestamp('accepted_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->unique(['tenant_id', 'order_number']);
                $table->index(['tenant_id', 'status']);
            });
        }

        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('service_template_id')->nullable()->constrained('service_templates')->nullOnDelete();
                $table->string('service_type', 40);
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('quantity', 10, 2)->default(1);
                $table->decimal('unit_cost', 12, 2)->default(0);
                $table->decimal('unit_price', 12, 2)->default(0);
                $table->decimal('line_total', 12, 2)->default(0);
                $table->decimal('line_profit', 12, 2)->default(0);
                $table->json('metadata')->nullable();
                $table->string('status', 24)->default('pending');
                $table->date('delivery_date')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->index(['order_id', 'service_type']);
            });
        }

        if (! Schema::hasColumn('quotes', 'order_id')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->foreignId('order_id')->nullable()->after('client_id')->constrained('orders')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('deals', 'order_id')) {
            Schema::table('deals', function (Blueprint $table) {
                $table->foreignId('order_id')->nullable()->after('client_id')->constrained('orders')->nullOnDelete();
            });
        }

        if (Schema::hasTable('deals') && ! Schema::hasColumn('deals', 'quote_opened_at')) {
            Schema::table('deals', function (Blueprint $table) {
                $table->timestamp('quote_opened_at')->nullable()->after('quote_was_opened');
                $table->timestamp('quote_accepted_at')->nullable()->after('quote_opened_at');
            });
        }

        if (! Schema::hasColumn('documents', 'order_item_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->foreignId('order_item_id')->nullable()->after('client_id')->constrained('order_items')->nullOnDelete();
                $table->timestamp('verified_at')->nullable()->after('uploaded_by');
                $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
                $table->string('document_status', 24)->nullable()->after('verified_by');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('documents', 'order_item_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropForeign(['order_item_id']);
                $table->dropForeign(['verified_by']);
                $table->dropColumn(['order_item_id', 'verified_at', 'verified_by', 'document_status']);
            });
        }

        if (Schema::hasColumn('deals', 'order_id')) {
            Schema::table('deals', function (Blueprint $table) {
                $table->dropForeign(['order_id']);
                $table->dropColumn(['order_id', 'quote_opened_at', 'quote_accepted_at']);
            });
        }

        if (Schema::hasColumn('quotes', 'order_id')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            });
        }

        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
