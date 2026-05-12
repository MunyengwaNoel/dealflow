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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('client_id')->constrained('clients');

            $table->enum('document_type', [
                'certificate_of_incorporation',
                'cr6',
                'cr14',
                'cr5',
                'memorandum_articles',
                'annual_return',
                'tax_clearance',
                'praz_certificate',
                'nssa_certificate',
                'business_plan',
                'company_profile',
                'domain_certificate',
                'other',
            ]);
            $table->string('title');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();

            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->unsignedInteger('reminder_days_before')->default(30);
            $table->longText('notes')->nullable();

            $table->foreignId('uploaded_by')->nullable()->constrained('users');
            $table->timestamp('last_reminded_at')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'client_id']);
            $table->index(['tenant_id', 'document_type']);
            $table->index(['tenant_id', 'expiry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
