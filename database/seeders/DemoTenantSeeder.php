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

class DemoTenantSeeder extends Seeder
{
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

            $svc = new TenantService;
            if (ServiceTemplate::query()->where('tenant_id', $tenant->id)->doesntExist()) {
                $svc->seedServiceTemplates($tenant);
            }

            if (Client::query()->where('tenant_id', $tenant->id)->exists()) {
                $this->command?->info('Demo tenant already exists with data; skipped re-seeding fixtures. Login: '.DemoUser::EMAIL.' / password');

                return;
            }
        } else {
            $svc = new TenantService;
            $created = $svc->createTenant([
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
        }

        app()->instance('tenant', $tenant);

        $faker = fake();
        $templates = ServiceTemplate::query()->where('tenant_id', $tenant->id)->get();

        $clients = collect();
        for ($i = 0; $i < 15; $i++) {
            $clients->push(Client::query()->create([
                'name' => $faker->company(),
                'trading_name' => $faker->optional()->company(),
                'email' => $faker->companyEmail(),
                'phone' => $faker->phoneNumber(),
                'city' => $faker->city(),
                'country' => $faker->countryCode(),
                'client_type' => $faker->randomElement(['individual', 'company']),
                'status' => $faker->randomElement(['active', 'active', 'inactive', 'prospect']),
                'assigned_to' => $owner->id,
            ]));
        }

        foreach ($clients as $client) {
            for ($d = 0; $d < $faker->numberBetween(1, 3); $d++) {
                $path = 'documents/'.$tenant->id.'/demo-'.$client->id.'-'.$d.'.txt';
                Storage::disk('local')->put($path, "Demo document content for client {$client->id}");
                Document::query()->create([
                    'client_id' => $client->id,
                    'document_type' => $faker->randomElement([
                    'certificate_of_incorporation',
                    'tax_clearance',
                    'praz_certificate',
                    'company_profile',
                    'other',
                ]),
                    'title' => $faker->sentence(3),
                    'file_path' => $path,
                    'file_size' => 128,
                    'mime_type' => 'text/plain',
                    'issue_date' => now()->subMonths(3)->toDateString(),
                    'expiry_date' => $faker->boolean(70)
                        ? now()->addDays($faker->numberBetween(5, 120))->toDateString()
                        : null,
                    'reminder_days_before' => 30,
                    'uploaded_by' => $owner->id,
                ]);
            }
        }

        $quoteSvc = new QuoteService;

        foreach ($clients->random(10) as $client) {
            $quote = Quote::query()->create([
                'client_id' => $client->id,
                'quote_number' => QuoteService::nextQuoteNumber($tenant->id),
                'status' => $faker->randomElement(['draft', 'sent', 'accepted', 'accepted', 'accepted']),
                'discount_amount' => 0,
                'discount_percent' => 0,
                'notes' => $faker->optional()->sentence(),
                'valid_until' => now()->addDays(30)->toDateString(),
                'created_by' => $owner->id,
            ]);

            $n = $faker->numberBetween(1, 4);
            for ($j = 0; $j < $n; $j++) {
                $tpl = $templates->random();
                QuoteItem::query()->create([
                    'quote_id' => $quote->id,
                    'service_template_id' => $tpl->id,
                    'name' => $tpl->name,
                    'description' => $tpl->description,
                    'cost_price' => $tpl->cost_price,
                    'sell_price' => $tpl->sell_price,
                    'quantity' => $faker->numberBetween(1, 5),
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

            if ($faker->boolean(60)) {
                (new InvoiceService)->recordPayment($inv, [
                    'amount' => min((float) $inv->total, (float) $inv->total * $faker->randomElement([0.5, 1.0])),
                    'payment_method' => $faker->randomElement(['cash', 'bank_transfer', 'ecocash']),
                    'payment_date' => now()->subDays($faker->numberBetween(0, 5))->toDateString(),
                ], $owner->id);
            }
        }

        foreach ($clients->random(12) as $client) {
            Deal::query()->create([
                'client_id' => $client->id,
                'title' => $faker->catchPhrase(),
                'description' => $faker->optional()->paragraph(),
                'stage' => $faker->randomElement(['lead', 'follow_up', 'proposal', 'negotiation', 'won', 'lost']),
                'priority' => $faker->randomElement(['low', 'medium', 'high']),
                'value' => $faker->randomFloat(2, 200, 25000),
                'expected_close_date' => now()->addDays($faker->numberBetween(-5, 45))->toDateString(),
                'assigned_to' => $owner->id,
            ]);
        }

        for ($c = 0; $c < 90; $c++) {
            CashflowEntry::query()->create([
                'entry_type' => $faker->randomElement(['income', 'expense']),
                'category' => $faker->randomElement(['sales', 'operations', 'marketing', 'other']),
                'description' => $faker->sentence(4),
                'amount' => $faker->randomFloat(2, 20, 5000),
                'payment_method' => $faker->randomElement(['cash', 'bank_transfer', 'other']),
                'entry_date' => now()->subDays($c)->toDateString(),
                'client_id' => $clients->random()->id,
                'recorded_by' => $owner->id,
            ]);
        }

        $this->command?->info('Demo tenant ready: '.DemoUser::EMAIL.' / password');
    }
}
