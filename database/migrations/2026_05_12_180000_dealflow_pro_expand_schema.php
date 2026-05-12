<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if (! Schema::hasColumn('tenants', 'settings')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->json('settings')->nullable()->after('stripe_customer_id');
            });
        }

        if (! Schema::hasColumn('users', 'last_login_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
            });
        }

        if ($driver === 'mysql') {
            $col = collect(DB::select('SHOW COLUMNS FROM users WHERE Field = ?', ['role']))->first();
            if ($col && str_contains((string) $col->Type, 'enum')) {
                DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(32) NOT NULL DEFAULT 'staff'");
            }
        }

        if (! Schema::hasColumn('clients', 'bp_number')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('bp_number')->nullable()->after('notes');
                $table->string('tin_number')->nullable()->after('bp_number');
                $table->date('registration_date')->nullable()->after('tin_number');
                $table->string('registered_address')->nullable()->after('registration_date');
                $table->json('physical_address')->nullable()->after('registered_address');
                $table->string('contact_person_name')->nullable()->after('physical_address');
                $table->string('contact_person_email')->nullable()->after('contact_person_name');
                $table->string('contact_person_phone')->nullable()->after('contact_person_email');
                $table->string('industry')->nullable()->after('contact_person_phone');
                $table->string('source')->nullable()->after('industry');
                $table->decimal('lifetime_value', 12, 2)->nullable()->after('source');
            });
        }

        if (! Schema::hasColumn('service_templates', 'template_code')) {
            Schema::table('service_templates', function (Blueprint $table) {
                $table->string('template_code', 32)->nullable()->after('tenant_id');
                $table->json('pricing_structure')->nullable()->after('sell_price');
                $table->json('demo_links')->nullable()->after('pricing_structure');
                $table->json('required_documents')->nullable()->after('demo_links');
                $table->json('deliverables')->nullable()->after('required_documents');
                $table->unsignedSmallInteger('timeline_days')->nullable()->after('deliverables');
                $table->json('automation_rules')->nullable()->after('timeline_days');
                $table->string('status', 20)->default('active')->after('automation_rules');
                $table->string('version_label', 16)->default('1.0')->after('status');
                $table->foreignId('created_by')->nullable()->after('version_label')->constrained('users')->nullOnDelete();
                $table->softDeletes();
            });
        }

        if (! $this->indexExists('service_templates', 'service_templates_tenant_template_code_unique')) {
            Schema::table('service_templates', function (Blueprint $table) {
                $table->unique(['tenant_id', 'template_code'], 'service_templates_tenant_template_code_unique');
            });
        }

        if (! Schema::hasTable('template_versions')) {
            Schema::create('template_versions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_template_id')->constrained('service_templates')->cascadeOnDelete();
                $table->string('version_number', 16);
                $table->text('changes_summary')->nullable();
                $table->json('template_data');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->index(['service_template_id', 'version_number']);
            });
        }

        if (! Schema::hasColumn('deals', 'priority_score')) {
            if ($driver === 'mysql') {
                DB::statement('ALTER TABLE deals MODIFY COLUMN stage VARCHAR(32) NOT NULL DEFAULT \'lead\'');
            }

            Schema::table('deals', function (Blueprint $table) {
                $table->string('deal_number', 40)->nullable()->after('client_id');
                $table->foreignId('service_template_id')->nullable()->after('deal_number')->constrained('service_templates')->nullOnDelete();
                $table->decimal('cost_total', 12, 2)->nullable()->after('value');
                $table->decimal('profit', 12, 2)->nullable()->after('cost_total');
                $table->decimal('profit_margin_percent', 5, 2)->nullable()->after('profit');
                $table->unsignedTinyInteger('probability_percent')->default(25)->after('profit_margin_percent');
                $table->unsignedSmallInteger('priority_score')->default(0)->after('probability_percent');
                $table->string('source')->nullable()->after('priority_score');
                $table->string('competitor_name')->nullable()->after('source');
                $table->boolean('quote_was_opened')->default(false)->after('competitor_name');
                $table->boolean('demo_was_viewed')->default(false)->after('quote_was_opened');
                $table->unsignedTinyInteger('engagement_points')->default(0)->after('demo_was_viewed');
            });

            DB::table('deals')->where('stage', 'follow_up')->update(['stage' => 'potential']);
            DB::table('deals')->where('stage', 'proposal')->update(['stage' => 'quoted']);

            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE deals MODIFY COLUMN stage ENUM('lead','potential','quoted','negotiation','won','lost') NOT NULL DEFAULT 'lead'");
                DB::statement("ALTER TABLE deals MODIFY COLUMN priority VARCHAR(20) NOT NULL DEFAULT 'warm'");
            }

            DB::table('deals')->whereIn('priority', ['high', 'urgent'])->update(['priority' => 'hot']);
            DB::table('deals')->where('priority', 'medium')->update(['priority' => 'warm']);
            DB::table('deals')->where('priority', 'low')->update(['priority' => 'cold']);
        }

        if (! Schema::hasColumn('quotes', 'deal_id')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->foreignId('deal_id')->nullable()->after('client_id')->constrained('deals')->nullOnDelete();
                $table->foreignId('service_template_id')->nullable()->after('deal_id')->constrained('service_templates')->nullOnDelete();
                $table->decimal('tax_amount', 10, 2)->default(0)->after('discount_percent');
                $table->text('payment_terms')->nullable()->after('tax_amount');
                $table->unsignedSmallInteger('validity_days')->default(30)->after('payment_terms');
                $table->json('demo_links')->nullable()->after('validity_days');
                $table->string('portal_token', 64)->nullable()->unique()->after('demo_links');
                $table->string('pdf_path')->nullable()->after('portal_token');
                $table->timestamp('sent_at')->nullable()->after('valid_until');
                $table->timestamp('viewed_at')->nullable()->after('sent_at');
                $table->timestamp('accepted_at')->nullable()->after('viewed_at');
                $table->timestamp('declined_at')->nullable()->after('accepted_at');
                $table->text('decline_reason')->nullable()->after('declined_at');
            });

            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE quotes MODIFY COLUMN status ENUM('draft','sent','viewed','accepted','declined','expired','invoiced') NOT NULL DEFAULT 'draft'");
            }
        }

        if (! Schema::hasTable('quote_analytics')) {
            Schema::create('quote_analytics', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quote_id')->constrained('quotes')->cascadeOnDelete();
                $table->string('event_type', 40);
                $table->json('event_data')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
                $table->index(['quote_id', 'event_type']);
            });
        }

        if (! Schema::hasTable('compliance_alerts')) {
            Schema::create('compliance_alerts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
                $table->foreignId('document_id')->nullable()->constrained('documents')->nullOnDelete();
                $table->string('alert_type', 60);
                $table->date('alert_date');
                $table->date('expiry_date')->nullable();
                $table->string('status', 20)->default('upcoming');
                $table->timestamp('notification_sent_at')->nullable();
                $table->date('snoozed_until')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
                $table->index(['tenant_id', 'status', 'alert_date']);
            });
        }

        $this->backfillDealNumbers();

        if (! $this->indexExists('deals', 'deals_tenant_deal_number_unique')) {
            Schema::table('deals', function (Blueprint $table) {
                $table->unique(['tenant_id', 'deal_number'], 'deals_tenant_deal_number_unique');
            });
        }
    }

    protected function indexExists(string $table, string $indexName): bool
    {
        return collect(Schema::getIndexes($table))
            ->contains(fn (array $index) => ($index['name'] ?? '') === $indexName);
    }

    protected function backfillDealNumbers(): void
    {
        if (! Schema::hasColumn('deals', 'priority_score')) {
            return;
        }

        $suffixMax = [];
        $rows = DB::table('deals')->whereNotNull('deal_number')->get(['id', 'tenant_id', 'deal_number']);
        foreach ($rows as $row) {
            $tid = (int) $row->tenant_id;
            if (preg_match('/^DL-'.$tid.'-(\d+)$/', (string) $row->deal_number, $m)) {
                $suffixMax[$tid] = max($suffixMax[$tid] ?? 0, (int) $m[1]);
            }
        }

        foreach (DB::table('deals')->whereNull('deal_number')->orderBy('id')->get(['id', 'tenant_id']) as $deal) {
            $tid = (int) $deal->tenant_id;
            $suffixMax[$tid] = ($suffixMax[$tid] ?? 0) + 1;
            $num = 'DL-'.$tid.'-'.str_pad((string) $suffixMax[$tid], 4, '0', STR_PAD_LEFT);
            DB::table('deals')->where('id', $deal->id)->update(['deal_number' => $num]);
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::dropIfExists('compliance_alerts');
        Schema::dropIfExists('quote_analytics');

        if (Schema::hasColumn('quotes', 'deal_id')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->dropForeign(['deal_id']);
                $table->dropForeign(['service_template_id']);
                $table->dropColumn([
                    'deal_id', 'service_template_id', 'tax_amount', 'payment_terms', 'validity_days',
                    'demo_links', 'portal_token', 'pdf_path', 'sent_at', 'viewed_at', 'accepted_at',
                    'declined_at', 'decline_reason',
                ]);
            });

            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE quotes MODIFY COLUMN status ENUM('draft','sent','accepted','declined','invoiced') NOT NULL DEFAULT 'draft'");
            }
        }

        if (Schema::hasColumn('deals', 'priority_score')) {
            if ($this->indexExists('deals', 'deals_tenant_deal_number_unique')) {
                Schema::table('deals', function (Blueprint $table) {
                    $table->dropUnique('deals_tenant_deal_number_unique');
                });
            }

            if ($driver === 'mysql') {
                DB::statement('ALTER TABLE deals MODIFY COLUMN stage VARCHAR(32) NOT NULL DEFAULT \'lead\'');
            }

            DB::table('deals')->where('stage', 'potential')->update(['stage' => 'follow_up']);
            DB::table('deals')->where('stage', 'quoted')->update(['stage' => 'proposal']);

            Schema::table('deals', function (Blueprint $table) {
                $table->dropForeign(['service_template_id']);
                $table->dropColumn([
                    'deal_number', 'service_template_id', 'cost_total', 'profit', 'profit_margin_percent',
                    'probability_percent', 'priority_score', 'source', 'competitor_name', 'quote_was_opened',
                    'demo_was_viewed', 'engagement_points',
                ]);
            });

            if ($driver === 'mysql') {
                DB::table('deals')->where('priority', 'hot')->update(['priority' => 'high']);
                DB::table('deals')->where('priority', 'warm')->update(['priority' => 'medium']);
                DB::table('deals')->whereIn('priority', ['cold', 'dead'])->update(['priority' => 'low']);
                DB::statement("ALTER TABLE deals MODIFY COLUMN stage ENUM('lead','follow_up','proposal','negotiation','won','lost') NOT NULL DEFAULT 'lead'");
                DB::statement("ALTER TABLE deals MODIFY COLUMN priority ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium'");
            }
        }

        Schema::dropIfExists('template_versions');

        if (Schema::hasColumn('service_templates', 'template_code')) {
            if ($this->indexExists('service_templates', 'service_templates_tenant_template_code_unique')) {
                Schema::table('service_templates', function (Blueprint $table) {
                    $table->dropUnique('service_templates_tenant_template_code_unique');
                });
            }
            Schema::table('service_templates', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropColumn([
                    'template_code', 'pricing_structure', 'demo_links', 'required_documents', 'deliverables',
                    'timeline_days', 'automation_rules', 'status', 'version_label', 'created_by', 'deleted_at',
                ]);
            });
        }

        if (Schema::hasColumn('clients', 'bp_number')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn([
                    'bp_number', 'tin_number', 'registration_date', 'registered_address', 'physical_address',
                    'contact_person_name', 'contact_person_email', 'contact_person_phone', 'industry', 'source',
                    'lifetime_value',
                ]);
            });
        }

        if ($driver === 'mysql') {
            $col = collect(DB::select('SHOW COLUMNS FROM users WHERE Field = ?', ['role']))->first();
            if ($col && ! str_contains((string) $col->Type, 'enum')) {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('owner','admin','staff') NOT NULL DEFAULT 'staff'");
            }
        }

        if (Schema::hasColumn('users', 'last_login_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('last_login_at');
            });
        }

        if (Schema::hasColumn('tenants', 'settings')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->dropColumn('settings');
            });
        }
    }
};
