<?php

namespace Database\Seeders;

use App\Models\CashflowEntry;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Document;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\ServiceTemplate;
use App\Models\Tenant;
use App\Models\User;
use App\Services\InvoiceService;
use App\Services\QuoteService;
use App\Services\TenantService;
use App\Support\DemoUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DemoTenantSeeder extends Seeder
{
    /** @return array<int, string> */
    protected function companyNames(): array
    {
        return [
            'Nyasha Logistics (Pvt) Ltd',
            'Chitungwiza Fresh Produce',
            'Bulawayo Steel Traders',
            'Mutare Timber & Hardware',
            'Harare Cloud Solutions',
            'Victoria Falls Tours Co.',
            'Gweru Auto Parts',
            'Masvingo Agritech',
            'Kwekwe Mining Supplies',
            'Bindura Citrus Exports',
            'Marondera Estates',
            'Rusape Retail Group',
            'Kariba Fisheries',
            'Chipinge Coffee Roasters',
            'Beitbridge Border Services',
        ];
    }

    /** @param  array<int, mixed>  $items */
    protected function pick(array $items): mixed
    {
        return $items[array_rand($items)];
    }

    public function run(): void
    {
        $slug = 'demo';

        $tenant = Tenant::query()->where('slug', $slug)->first();

        if ($tenant) {
            $owner = User::query()->updateOrCreate(
                ['email' => DemoUser::EMAIL],
                [
                    'tenant_id' => $tenant->id,
                    'name' => 'Demo Owner',
                    'password' => 'password',
                    'role' => 'owner',
                ]
            );

            $tenant->forceFill([
                'name' => 'Demo Company Ltd',
                'plan' => 'pro',
                'email' => 'accounts@demo.biztrack.app',
                'phone' => '+263 77 000 0000',
                'owner_id' => $owner->id,
            ])->save();

            (new TenantService)->ensureDealFlowCatalog($tenant, $owner->id);

            if (Client::query()->where('tenant_id', $tenant->id)->exists()) {
                $this->command?->info('Demo tenant already exists with data; skipped re-seeding fixtures. Login: '.DemoUser::EMAIL.' / password');

                return;
            }
        } else {
            $created = (new TenantService)->createTenant([
                'name' => 'Demo Company Ltd',
                'slug' => $slug,
                'plan' => 'pro',
                'email' => 'accounts@demo.biztrack.app',
                'phone' => '+263 77 000 0000',
                'owner_name' => 'Demo Owner',
                'owner_email' => DemoUser::EMAIL,
                'owner_password' => 'password',
            ]);

            $tenant = $created['tenant'];
            $owner = $created['owner'];
            (new TenantService)->ensureDealFlowCatalog($tenant, $owner->id);
        }

        app()->instance('tenant', $tenant);

        $templates = ServiceTemplate::query()->where('tenant_id', $tenant->id)->get();
        $names = $this->companyNames();

        $clients = collect();
        foreach (range(0, 14) as $i) {
            $suffix = Str::lower(Str::random(6));
            $clients->push(Client::query()->create([
                'name' => $names[$i],
                'trading_name' => random_int(0, 1) ? $names[$i].' Trading' : null,
                'email' => 'client-'.$suffix.'@demo-mail.invalid',
                'phone' => '+263 77 '.str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT),
                'city' => $this->pick(['Harare', 'Bulawayo', 'Mutare', 'Gweru', 'Masvingo', 'Kwekwe']),
                'country' => 'ZW',
                'client_type' => $this->pick(['individual', 'company']),
                'status' => $this->pick(['active', 'active', 'inactive', 'prospect']),
                'assigned_to' => $owner->id,
            ]));
        }

        $docTypes = [
            'certificate_of_incorporation',
            'tax_clearance',
            'praz_certificate',
            'company_profile',
            'other',
        ];

        foreach ($clients as $client) {
            $docCount = random_int(1, 3);
            for ($d = 0; $d < $docCount; $d++) {
                $path = 'documents/'.$tenant->id.'/demo-'.$client->id.'-'.$d.'.txt';
                Storage::disk('local')->put($path, "Demo document content for client {$client->id}");
                Document::query()->create([
                    'client_id' => $client->id,
                    'document_type' => $this->pick($docTypes),
                    'title' => 'Demo document '.($d + 1).' for '.$client->name,
                    'file_path' => $path,
                    'file_size' => 128,
                    'mime_type' => 'text/plain',
                    'issue_date' => now()->subMonths(3)->toDateString(),
                    'expiry_date' => random_int(1, 100) <= 70
                        ? now()->addDays(random_int(5, 120))->toDateString()
                        : null,
                    'reminder_days_before' => 30,
                    'uploaded_by' => $owner->id,
                ]);
            }
        }

        $quoteSvc = new QuoteService;

        $quoteStatuses = ['draft', 'sent', 'viewed', 'accepted', 'accepted', 'accepted'];

        foreach ($clients->random(min(10, $clients->count())) as $client) {
            $quote = Quote::query()->create([
                'client_id' => $client->id,
                'quote_number' => QuoteService::nextQuoteNumber($tenant->id),
                'status' => $this->pick($quoteStatuses),
                'discount_amount' => 0,
                'discount_percent' => 0,
                'notes' => random_int(0, 1) ? 'Demo quote notes — follow up within 7 days.' : null,
                'valid_until' => now()->addDays(30)->toDateString(),
                'created_by' => $owner->id,
            ]);

            $n = random_int(1, 4);
            for ($j = 0; $j < $n; $j++) {
                $tpl = $templates->random();
                QuoteItem::query()->create([
                    'quote_id' => $quote->id,
                    'service_template_id' => $tpl->id,
                    'name' => $tpl->name,
                    'description' => $tpl->description,
                    'cost_price' => $tpl->cost_price,
                    'sell_price' => $tpl->sell_price,
                    'quantity' => random_int(1, 5),
                    'line_total' => 0,
                ]);
            }
            $quoteSvc->recalculate($quote);
        }

        foreach (Quote::query()->where('status', 'accepted')->take(5)->get() as $quote) {
            if ($quote->invoice) {
                continue;
            }
            $inv = $quoteSvc->convertToInvoice($quote, $owner->id);
            $inv->update(['status' => 'sent', 'due_date' => now()->addDays(7)->toDateString()]);

            if (random_int(1, 100) <= 60) {
                (new InvoiceService)->recordPayment($inv, [
                    'amount' => min((float) $inv->total, (float) $inv->total * $this->pick([0.5, 1.0])),
                    'payment_method' => $this->pick(['cash', 'bank_transfer', 'ecocash']),
                    'payment_date' => now()->subDays(random_int(0, 5))->toDateString(),
                ], $owner->id);
            }
        }

        $stages = ['lead', 'potential', 'quoted', 'negotiation', 'won', 'lost'];
        $priorities = ['hot', 'warm', 'cold'];
        $phrases = [
            'Website refresh and hosting bundle',
            'Annual compliance package',
            'Company registration + domain',
            'Tax clearance renewal',
            'Starter website + email hosting',
        ];

        foreach ($clients->random(min(12, $clients->count())) as $client) {
            Deal::query()->create([
                'client_id' => $client->id,
                'title' => $this->pick($phrases),
                'description' => random_int(0, 1) ? 'Demo deal created by DemoTenantSeeder for dashboard previews.' : null,
                'stage' => $this->pick($stages),
                'priority' => $this->pick($priorities),
                'value' => round(random_int(20000, 2500000) / 100, 2),
                'expected_close_date' => now()->addDays(random_int(-5, 45))->toDateString(),
                'assigned_to' => $owner->id,
            ]);
        }

        for ($c = 0; $c < 90; $c++) {
            CashflowEntry::query()->create([
                'entry_type' => $this->pick(['income', 'expense']),
                'category' => $this->pick(['sales', 'operations', 'marketing', 'other']),
                'description' => 'Demo cashflow entry #'.($c + 1).' — seeded for charts.',
                'amount' => round(random_int(2000, 500000) / 100, 2),
                'payment_method' => $this->pick(['cash', 'bank_transfer', 'other']),
                'entry_date' => now()->subDays($c)->toDateString(),
                'client_id' => $clients->random()->id,
                'recorded_by' => $owner->id,
            ]);
        }

        $this->command?->info('Demo tenant ready: '.DemoUser::EMAIL.' / password');
    }
}
