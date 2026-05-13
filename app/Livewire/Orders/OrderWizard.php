<?php

namespace App\Livewire\Orders;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use App\Jobs\ProcessOrderQuoteJob;
use App\Models\Client;
use App\Models\Order;
use App\Services\DomainAvailabilityService;
use App\Services\PriceCalculator;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderWizard extends Component
{
    public ?int $orderId = null;

    public int $stepIndex = 0;

    /** @var list<string> */
    public array $stepKeys = ['customer', 'services', 'review'];

    public ?int $clientId = null;

    /** @var list<string> */
    public array $selectedServices = [];

    public string $clientSearch = '';

    public string $domainPrefix = '';

    public string $domainExtension = '.co.zw';

    public int $domainYears = 3;

    public bool $domainPrivacy = false;

    public bool $domainAvailabilityChecked = false;

    public ?bool $domainAvailable = null;

    /** @var list<string> */
    public array $domainSuggestions = [];

    public string $websitePackage = 'business';

    /** @var list<string> */
    public array $websiteAddons = [];

    public bool $websiteHostingAuto = true;

    public string $businessType = '';

    public string $emailPackage = 'business';

    /** @var list<array{prefix:string,purpose:string}> */
    public array $emailAddresses = [
        ['prefix' => 'info', 'purpose' => 'general'],
        ['prefix' => 'sales', 'purpose' => 'sales'],
        ['prefix' => 'support', 'purpose' => 'support'],
    ];

    public string $companyType = 'pvt_ltd';

    /** @var list<string> */
    public array $companyNameOptions = ['', '', ''];

    /** @var list<array{name:string,id_number:string,address:string,shares:string,nationality:string}> */
    public array $directors = [
        ['name' => '', 'id_number' => '', 'address' => '', 'shares' => '100', 'nationality' => 'Zimbabwean'],
    ];

    /** @var list<string> */
    public array $companyAddons = [];

    public int $taxUrgent = 0;

    public string $taxFrequency = 'once';

    /** Paid social / digital ads (Meta, Instagram, TikTok) */
    public string $adsPlatformBundle = 'meta';

    public string $adsCampaignName = '';

    public ?string $adsCampaignEndDate = null;

    public string $adsCampaignEndTime = '23:59';

    public string $adsTimezone = 'Africa/Harare';

    /** @var list<string> */
    public array $adsAddons = [];

    public string $paymentTerms = 'deposit';

    public bool $sendEmail = true;

    public bool $sendWhatsapp = true;

    public bool $sendSms = false;

    public string $personalMessage = '';

    public function mount(?int $resume = null, ?int $prefillClient = null): void
    {
        if ($resume) {
            $this->loadOrder($resume);
        }
        if ($prefillClient && ! $this->clientId) {
            $this->selectClient($prefillClient);
        }
    }

    protected function loadOrder(int $id): void
    {
        $order = Order::query()->whereKey($id)->firstOrFail();
        $this->orderId = $order->id;
        $this->clientId = $order->client_id;
        $s = $order->wizard_state ?? [];
        $this->selectedServices = $s['selected_services'] ?? [];
        $this->stepKeys = $s['step_keys'] ?? $this->defaultSteps();
        $this->stepIndex = (int) ($s['step_index'] ?? 0);
        $this->hydrateFromState($s);
    }

    public function updatedClientId(mixed $value): void
    {
        if ($value === null || $value === '' || $value === '0') {
            $this->clientId = null;

            return;
        }
        $id = (int) $value;
        if ($id < 1) {
            $this->clientId = null;

            return;
        }
        $this->applyClientDefaults(Client::query()->findOrFail($id));
    }

    /**
     * @return Collection<int, Client>
     */
    protected function pickerClients(): Collection
    {
        $query = Client::query()->withCount('quotes');

        $term = trim($this->clientSearch);
        if (strlen($term) >= 2) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%')
                    ->orWhere('trading_name', 'like', '%'.$term.'%')
                    ->orWhere('email', 'like', '%'.$term.'%')
                    ->orWhere('phone', 'like', '%'.$term.'%');
            })
                ->orderBy('name');
        } else {
            $query->orderByDesc('quotes_count')->orderBy('name');
        }

        $rows = $query->limit(50)->get();
        if ($this->clientId && ! $rows->contains(fn (Client $c): bool => $c->id === $this->clientId)) {
            $current = Client::query()->withCount('quotes')->find($this->clientId);
            if ($current) {
                $rows->prepend($current);
            }
        }

        return $rows;
    }

    public function selectClient(int $id): void
    {
        $this->clientId = $id;
        $this->applyClientDefaults(Client::query()->findOrFail($id));
    }

    protected function applyClientDefaults(Client $client): void
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '', $client->trading_name ?: $client->name) ?? '');
        if ($slug !== '' && $this->domainPrefix === '') {
            $this->domainPrefix = substr($slug, 0, 20);
        }
        if ($this->directors[0]['name'] === '') {
            $this->directors[0]['name'] = $client->contact_person_name ?: $client->name;
            $this->directors[0]['address'] = $client->registered_address ?: (string) $client->address;
        }
    }

    public function updatedSelectedServices(): void
    {
        $this->rebuildSteps();
        if (in_array('website', $this->selectedServices, true) && ! in_array('domain', $this->selectedServices, true)) {
            $this->websiteHostingAuto = true;
        }
    }

    protected function rebuildSteps(): void
    {
        $seq = ['customer', 'services'];
        $order = ['domain', 'website', 'email', 'company_reg', 'tax_clearance', 'business_plan', 'paid_social'];
        foreach ($order as $k) {
            if (in_array($k, $this->selectedServices, true)) {
                $seq[] = $k;
            }
        }
        $seq[] = 'review';
        $this->stepKeys = $seq;
        $this->stepIndex = min($this->stepIndex, count($this->stepKeys) - 1);
    }

    /** @return list<string> */
    protected function defaultSteps(): array
    {
        return ['customer', 'services', 'review'];
    }

    public function currentKey(): string
    {
        return $this->stepKeys[$this->stepIndex] ?? 'customer';
    }

    public function goStep(int $idx): void
    {
        if ($idx >= 0 && $idx < count($this->stepKeys)) {
            $this->stepIndex = $idx;
        }
    }

    public function next(): void
    {
        if (! $this->validateCurrent()) {
            return;
        }
        $this->persistDraft();
        if ($this->stepIndex < count($this->stepKeys) - 1) {
            $this->stepIndex++;
        }
    }

    public function back(): void
    {
        $this->persistDraft();
        if ($this->stepIndex > 0) {
            $this->stepIndex--;
        }
    }

    public function checkDomain(DomainAvailabilityService $domains): void
    {
        $this->validate([
            'domainPrefix' => ['required', 'regex:/^[a-zA-Z0-9-]+$/'],
            'domainExtension' => ['required', 'string'],
        ]);
        $fqdn = rtrim($this->domainPrefix, '.').$this->domainExtension;
        $res = $domains->check($fqdn);
        $this->domainAvailable = $res['available'];
        $this->domainSuggestions = $res['suggestions'];
        $this->domainAvailabilityChecked = true;
    }

    public function saveDraft(): void
    {
        $this->persistDraft();
        Notification::make()->title('Draft saved')->success()->send();
    }

    protected function persistDraft(): void
    {
        $tenantId = Auth::user()?->tenant_id;
        if (! $tenantId || ! $this->clientId) {
            return;
        }
        $state = $this->serializeState();
        if ($this->orderId) {
            $calc = app(PriceCalculator::class);
            $lines = $calc->linesFromWizardState($state);
            $totals = $calc->compute($lines);
            Order::query()->whereKey($this->orderId)->update([
                'wizard_state' => $state,
                'client_id' => $this->clientId,
                'total_amount' => $totals['subtotal'],
                'total_cost' => $totals['total_cost'],
                'profit_amount' => $totals['profit'],
                'profit_margin' => $totals['margin_percent'],
            ]);

            return;
        }
        $calc = app(PriceCalculator::class);
        $lines = $calc->linesFromWizardState($state);
        $totals = $calc->compute($lines);
        $order = Order::query()->create([
            'tenant_id' => $tenantId,
            'client_id' => $this->clientId,
            'status' => OrderStatus::Draft,
            'wizard_state' => $state,
            'total_amount' => $totals['subtotal'],
            'total_cost' => $totals['total_cost'],
            'profit_amount' => $totals['profit'],
            'profit_margin' => $totals['margin_percent'],
            'created_by' => Auth::id(),
        ]);
        $this->orderId = $order->id;
    }

    /**
     * @return array<string, mixed>
     */
    protected function serializeState(): array
    {
        $expiry = now()->addYears($this->domainYears)->toDateString();

        return [
            'step_index' => $this->stepIndex,
            'step_keys' => $this->stepKeys,
            'selected_services' => $this->selectedServices,
            'domain' => [
                'prefix' => $this->domainPrefix,
                'extension' => $this->domainExtension,
                'period_years' => $this->domainYears,
                'privacy' => $this->domainPrivacy,
                'availability_checked' => $this->domainAvailabilityChecked,
                'available' => $this->domainAvailable,
                'expiry_date' => $expiry,
            ],
            'website' => [
                'package' => $this->websitePackage,
                'addons' => $this->websiteAddons,
                'hosting_auto' => $this->websiteHostingAuto || in_array('email', $this->selectedServices, true),
                'industry' => $this->businessType,
            ],
            'email' => [
                'package' => $this->emailPackage,
                'addresses' => $this->emailAddresses,
                'expiry_date' => now()->addYear()->toDateString(),
            ],
            'company_reg' => [
                'company_type' => $this->companyType,
                'name_options' => $this->companyNameOptions,
                'directors' => $this->directors,
                'addons' => $this->companyAddons,
            ],
            'tax_clearance' => [
                'urgent' => $this->taxUrgent === 1,
                'frequency' => $this->taxFrequency,
            ],
            'paid_social' => [
                'platform_bundle' => $this->adsPlatformBundle,
                'campaign_name' => $this->adsCampaignName,
                'campaign_end_date' => $this->adsCampaignEndDate,
                'campaign_end_time' => $this->adsCampaignEndTime,
                'timezone' => $this->adsTimezone,
                'addons' => $this->adsAddons,
            ],
            'review' => [
                'payment_terms' => $this->paymentTerms,
                'personal_message' => $this->personalMessage,
            ],
            'demo_links' => [],
        ];
    }

    /**
     * @param  array<string, mixed>  $s
     */
    protected function hydrateFromState(array $s): void
    {
        $d = $s['domain'] ?? [];
        $this->domainPrefix = (string) ($d['prefix'] ?? '');
        $this->domainExtension = (string) ($d['extension'] ?? '.co.zw');
        $this->domainYears = (int) ($d['period_years'] ?? 3);
        $this->domainPrivacy = (bool) ($d['privacy'] ?? false);
        $this->domainAvailabilityChecked = (bool) ($d['availability_checked'] ?? false);
        $this->domainAvailable = isset($d['available']) ? (bool) $d['available'] : null;
        $w = $s['website'] ?? [];
        $this->websitePackage = (string) ($w['package'] ?? 'business');
        $this->websiteAddons = $w['addons'] ?? [];
        $this->websiteHostingAuto = (bool) ($w['hosting_auto'] ?? true);
        $this->businessType = (string) ($w['industry'] ?? '');
        $e = $s['email'] ?? [];
        $this->emailPackage = (string) ($e['package'] ?? 'business');
        $this->emailAddresses = $e['addresses'] ?? $this->emailAddresses;
        $c = $s['company_reg'] ?? [];
        $this->companyType = (string) ($c['company_type'] ?? 'pvt_ltd');
        $this->companyNameOptions = $c['name_options'] ?? ['', '', ''];
        $this->directors = $c['directors'] ?? $this->directors;
        $this->companyAddons = $c['addons'] ?? [];
        $t = $s['tax_clearance'] ?? [];
        $this->taxUrgent = (int) ($t['urgent'] ?? 0);
        $this->taxFrequency = (string) ($t['frequency'] ?? 'once');
        $ps = $s['paid_social'] ?? [];
        $this->adsPlatformBundle = (string) ($ps['platform_bundle'] ?? 'meta');
        $this->adsCampaignName = (string) ($ps['campaign_name'] ?? '');
        $this->adsCampaignEndDate = isset($ps['campaign_end_date']) ? (string) $ps['campaign_end_date'] : null;
        $this->adsCampaignEndTime = (string) ($ps['campaign_end_time'] ?? '23:59');
        $this->adsTimezone = (string) ($ps['timezone'] ?? 'Africa/Harare');
        $this->adsAddons = $ps['addons'] ?? [];
        $r = $s['review'] ?? [];
        if (array_key_exists('payment_terms', $r)) {
            $this->paymentTerms = (string) $r['payment_terms'];
        }
        if (array_key_exists('personal_message', $r)) {
            $this->personalMessage = (string) $r['personal_message'];
        }
    }

    protected function validateCurrent(): bool
    {
        $key = $this->currentKey();
        if ($key === 'customer') {
            $this->validate(['clientId' => 'required|integer|exists:clients,id']);

            return true;
        }
        if ($key === 'services') {
            $this->validate(['selectedServices' => 'required|array|min:1']);

            return true;
        }
        if ($key === 'domain') {
            $this->validate([
                'domainPrefix' => ['required', 'regex:/^[a-zA-Z0-9-]+$/'],
                'domainExtension' => ['required', 'string'],
            ]);
            if (! $this->domainAvailabilityChecked) {
                $this->addError('domainPrefix', 'Check availability before continuing.');

                return false;
            }
            if ($this->domainAvailable !== true) {
                $this->addError('domainPrefix', 'Choose an available domain before continuing.');

                return false;
            }

            return true;
        }
        if ($key === 'website') {
            $this->validate(['websitePackage' => 'required|in:basic,business,ecommerce']);

            return true;
        }
        if ($key === 'email') {
            $this->validate([
                'emailPackage' => 'required|in:starter,business,enterprise',
                'emailAddresses' => 'required|array|min:1',
                'emailAddresses.*.prefix' => ['required', 'regex:/^[a-zA-Z0-9._-]+$/'],
            ]);
            $prefixes = collect($this->emailAddresses)->pluck('prefix')->map(fn ($p) => strtolower((string) $p))->filter();
            if ($prefixes->count() !== $prefixes->unique()->count()) {
                $this->addError('emailAddresses', 'Each email prefix must be unique.');

                return false;
            }

            return true;
        }
        if ($key === 'company_reg') {
            $this->validate([
                'companyType' => 'required|string',
                'companyNameOptions' => 'required|array|min:3',
                'companyNameOptions.*' => 'required|string|min:2',
                'directors' => 'required|array|min:1',
                'directors.*.name' => 'required|string|min:2',
                'directors.*.id_number' => 'nullable|string|max:64',
                'directors.*.shares' => 'required|numeric|min:0|max:100',
            ]);
            $total = collect($this->directors)->sum(fn ($d) => (float) ($d['shares'] ?? 0));
            if (abs($total - 100) > 0.01) {
                $this->addError('directors', 'Total shareholding must equal 100%.');

                return false;
            }

            return true;
        }
        if ($key === 'tax_clearance') {
            $this->validate(['taxFrequency' => 'required|string']);

            return true;
        }
        if ($key === 'paid_social') {
            $this->validate([
                'adsCampaignName' => 'required|string|min:3|max:120',
                'adsCampaignEndDate' => 'required|date',
                'adsCampaignEndTime' => ['required', 'regex:/^\d{1,2}:\d{2}$/'],
                'adsTimezone' => 'required|string|max:64',
                'adsPlatformBundle' => 'required|in:meta,tiktok,bundle',
            ]);

            return true;
        }
        if ($key === 'review') {
            $this->validate(['paymentTerms' => 'required|in:deposit,full,installments']);

            return true;
        }

        return true;
    }

    public function updatedPaymentTerms(): void
    {
        if ($this->orderId) {
            $this->persistDraft();
        }
    }

    public function updatedPersonalMessage(): void
    {
        if ($this->orderId) {
            $this->persistDraft();
        }
    }

    public function sendQuote(): void
    {
        if (! $this->validateCurrent()) {
            return;
        }
        $this->persistDraft();
        if (! $this->orderId) {
            Notification::make()->title('Save a draft first')->danger()->send();

            return;
        }
        $terms = match ($this->paymentTerms) {
            'full' => 'Full payment upfront',
            'installments' => 'Three equal installments',
            default => '50% deposit, 50% on delivery',
        };
        ProcessOrderQuoteJob::dispatch(
            $this->orderId,
            (int) Auth::id(),
            [
                'payment_terms' => $terms,
                'personal_message' => $this->personalMessage,
                'send_email' => $this->sendEmail,
            ]
        );
        Notification::make()->title('Quote queued')->body('PDF, deal, and compliance tasks are being generated.')->success()->send();
        $this->redirect(OrderResource::getUrl('index'));
    }

    public function addDirector(): void
    {
        $this->directors[] = ['name' => '', 'id_number' => '', 'address' => '', 'shares' => '0', 'nationality' => 'Zimbabwean'];
    }

    public function addEmailRow(): void
    {
        $this->emailAddresses[] = ['prefix' => '', 'purpose' => ''];
    }

    public function toggleService(string $key): void
    {
        if (in_array($key, $this->selectedServices, true)) {
            $this->selectedServices = array_values(array_filter($this->selectedServices, fn ($k) => $k !== $key));
        } else {
            $this->selectedServices[] = $key;
        }
        $this->rebuildSteps();
    }

    public function toggleWebsiteAddon(string $key): void
    {
        if (in_array($key, $this->websiteAddons, true)) {
            $this->websiteAddons = array_values(array_filter($this->websiteAddons, fn ($k) => $k !== $key));
        } else {
            $this->websiteAddons[] = $key;
        }
    }

    public function pickDomainSuggestion(string $fqdn): void
    {
        $parts = explode('.', $fqdn, 2);
        $this->domainPrefix = $parts[0] ?? $fqdn;
        if (isset($parts[1])) {
            $this->domainExtension = '.'.$parts[1];
        }
        $this->domainAvailabilityChecked = false;
        $this->domainAvailable = null;
    }

    public function toggleCompanyAddon(string $key): void
    {
        if (in_array($key, $this->companyAddons, true)) {
            $this->companyAddons = array_values(array_filter($this->companyAddons, fn ($k) => $k !== $key));
        } else {
            $this->companyAddons[] = $key;
        }
    }

    public function togglePaidSocialAddon(string $key): void
    {
        if (in_array($key, $this->adsAddons, true)) {
            $this->adsAddons = array_values(array_filter($this->adsAddons, fn ($k) => $k !== $key));
        } else {
            $this->adsAddons[] = $key;
        }
    }

    public function render(PriceCalculator $calculator)
    {
        $summary = $calculator->compute($calculator->linesFromWizardState($this->serializeState()));

        return view('livewire.orders.order-wizard', [
            'pricing' => $summary,
            'clientName' => $this->clientId ? Client::query()->whereKey($this->clientId)->value('name') : '',
            'recentClients' => Client::query()->withCount('quotes')->orderByDesc('quotes_count')->limit(6)->get(),
            'pickerClients' => $this->pickerClients(),
            'orderId' => $this->orderId,
        ]);
    }
}
