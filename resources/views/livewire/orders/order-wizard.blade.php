<div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <div class="grid grid-cols-1 gap-6 p-4 lg:grid-cols-12">
        <div class="lg:col-span-8 space-y-6">
            {{-- Progress --}}
            <div>
                <div class="flex flex-wrap items-center justify-between gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-semibold text-gray-900 dark:text-white">
                        Step {{ $stepIndex + 1 }} of {{ count($stepKeys) }}
                    </span>
                    <span>{{ $clientName ?: 'Select a customer' }}</span>
                </div>
                <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                    <div class="h-full rounded-full bg-primary-600 transition-all"
                         style="width: {{ count($stepKeys) ? (($stepIndex + 1) / count($stepKeys)) * 100 : 0 }}%"></div>
                </div>
                <nav class="mt-3 flex flex-wrap gap-2 text-xs">
                    @foreach($stepKeys as $i => $key)
                        <button type="button"
                                wire:click="goStep({{ $i }})"
                                class="rounded-full px-2 py-1 {{ $i === $stepIndex ? 'bg-primary-600 text-white' : ($i < $stepIndex ? 'bg-emerald-50 text-emerald-800 dark:bg-emerald-900/30' : 'bg-gray-100 text-gray-600 dark:bg-gray-800') }}">
                            {{ str($key)->replace('_', ' ')->title() }}
                        </button>
                    @endforeach
                </nav>
            </div>

            @php $k = $this->currentKey(); @endphp

            @if($k === 'customer')
                <div class="space-y-4">
                    <div>
                        <label for="client-search" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Customer</label>
                        <p id="client-search-hint" class="mt-1 text-xs text-gray-500 dark:text-gray-400">Type at least 2 characters to search by name, trading name, email, or phone — or pick from the list.</p>
                        <input id="client-search" type="search" wire:model.live.debounce.300ms="clientSearch" autocomplete="off" aria-describedby="client-search-hint"
                               class="fi-input mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600 dark:border-gray-700 dark:bg-gray-900"
                               placeholder="Search to filter the list…"/>
                    </div>
                    <div>
                        <label for="client-picker" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Choose customer</label>
                        <select id="client-picker" wire:model.live="clientId" aria-label="Choose customer"
                                class="fi-input mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-600 focus:ring-primary-600 dark:border-gray-700 dark:bg-gray-900">
                            <option value="">{{ __('— Select a customer —') }}</option>
                            @foreach($pickerClients as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}@if($c->trading_name) · {{ $c->trading_name }}@endif ({{ $c->quotes_count }} quotes)</option>
                            @endforeach
                        </select>
                        @if($pickerClients->isEmpty())
                            <p class="mt-2 text-xs text-amber-700 dark:text-amber-300">{{ __('No customers match that search. Try another term or clear the search to see suggested clients.') }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Quick picks') }}</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($recentClients as $rc)
                                <button type="button" wire:click="selectClient({{ $rc->id }})"
                                        class="rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs font-medium text-gray-800 hover:border-primary-500 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                                    {{ $rc->name }}
                                    <span class="ml-1 rounded-full bg-white px-1.5 text-[10px] dark:bg-gray-900">{{ $rc->quotes_count }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($k === 'services')
                <div class="space-y-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">What does {{ $clientName }} need?</h2>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach([
                            'website' => ['🌐','Website development',150],
                            'email' => ['📧','Email hosting',24],
                            'company_reg' => ['🏢','Company registration',120],
                            'domain' => ['🌍','Domain registration',9],
                            'tax_clearance' => ['💰','Tax clearance',50],
                            'business_plan' => ['📑','Business plan',200],
                            'paid_social' => ['📣','Paid social ads',280],
                        ] as $svc => $meta)
                            <button type="button" wire:click="toggleService('{{ $svc }}')"
                                    class="flex flex-col rounded-xl border p-4 text-left transition
                                        {{ in_array($svc, $selectedServices, true) ? 'border-primary-600 ring-2 ring-primary-500/30 bg-primary-50/40 dark:bg-primary-950/20' : 'border-gray-200 hover:border-primary-400 dark:border-gray-700' }}">
                                <span class="text-2xl">{{ $meta[0] }}</span>
                                <span class="mt-2 font-semibold text-gray-900 dark:text-white">{{ $meta[1] }}</span>
                                <span class="text-sm text-gray-500">from ${{ $meta[2] }}</span>
                                <span class="mt-3 text-xs font-bold text-primary-700">{{ in_array($svc, $selectedServices, true) ? '✓ Selected' : 'Select' }}</span>
                            </button>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-600">Estimated total: <strong>${{ number_format($pricing['subtotal'], 2) }}</strong></p>
                </div>
            @endif

            @if($k === 'domain')
                <div class="space-y-4">
                    <h2 class="text-lg font-bold">🌍 Domain registration</h2>
                    <div class="flex flex-wrap gap-2">
                        <input wire:model="domainPrefix" class="fi-input rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="yourbrand"/>
                        <select wire:model="domainExtension" class="fi-input rounded-lg border-gray-300 dark:bg-gray-700 dark:bg-gray-900">
                            <option value=".co.zw">.co.zw</option>
                            <option value=".com">.com</option>
                            <option value=".net">.net</option>
                            <option value=".org">.org</option>
                            <option value=".africa">.africa</option>
                        </select>
                        <button type="button" wire:click="checkDomain" wire:loading.attr="disabled"
                                class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-500">
                            Check availability
                        </button>
                    </div>
                    @if($domainAvailabilityChecked)
                        <p class="{{ $domainAvailable ? 'text-emerald-600' : 'text-red-600' }} font-semibold">
                            {{ $domainAvailable ? '✅ Available' : '❌ Taken' }}
                        </p>
                        @if(!$domainAvailable && count($domainSuggestions))
                            <div class="flex flex-wrap gap-2">
                                @foreach($domainSuggestions as $s)
                                    <button type="button" wire:click="pickDomainSuggestion(@js($s))" class="text-xs underline">{{ $s }}</button>
                                @endforeach
                            </div>
                        @endif
                    @endif
                    <div class="grid gap-3 sm:grid-cols-3">
                        @foreach([1 => '1 year', 2 => '2 years', 3 => '3 years ✨'] as $y => $label)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                                <input type="radio" wire:model="domainYears" value="{{ $y }}" class="text-primary-600"/>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="domainPrivacy" class="rounded border-gray-300 text-primary-600"/>
                        Domain privacy (+$5/yr)
                    </label>
                </div>
            @endif

            @if($k === 'website')
                <div class="space-y-4">
                    <h2 class="text-lg font-bold">🌐 Website development</h2>
                    <input wire:model.live="businessType" class="fi-input w-full rounded-lg border-gray-300 dark:bg-gray-900" placeholder="Business type (e.g. perfume sales)"/>
                    <div class="grid gap-3 md:grid-cols-3">
                        @foreach([
                            'basic' => ['Basic',150,'5 pages · contact form'],
                            'business' => ['Business',350,'10 pages · blog · gallery'],
                            'ecommerce' => ['E-commerce',800,'Cart · payments'],
                        ] as $id => $row)
                            <button type="button" wire:click="$set('websitePackage','{{ $id }}')"
                                    class="rounded-xl border p-4 text-left {{ $websitePackage === $id ? 'border-primary-600 ring-2 ring-primary-500/30' : 'border-gray-200 dark:border-gray-700' }}">
                                <div class="font-bold">{{ $row[0] }}</div>
                                <div class="text-primary-700 font-semibold">${{ $row[1] }}</div>
                                <p class="mt-2 text-xs text-gray-500">{{ $row[2] }}</p>
                            </button>
                        @endforeach
                    </div>
                    @if(str_contains(strtolower($businessType), 'perfume'))
                        <p class="text-sm text-amber-800 bg-amber-50 rounded-lg p-2">💡 For perfume sales, we recommend Business or E-commerce.</p>
                    @endif
                    <div class="space-y-2 text-sm">
                        @foreach([
                            'live_chat' => 'Live chat (+$50)',
                            'custom_design' => 'Custom design (+$150)',
                            'booking' => 'Booking system (+$100)',
                            'maintenance' => 'Monthly maintenance ($360/yr)',
                            'content_write' => 'We write content (+$200)',
                        ] as $aid => $alabel)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:click="toggleWebsiteAddon('{{ $aid }}')" @checked(in_array($aid, $websiteAddons, true))/>
                                {{ $alabel }}
                            </label>
                        @endforeach
                    </div>
                    @if(in_array('website', $selectedServices, true))
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" wire:model="websiteHostingAuto"/>
                            Auto-include web hosting ($30/yr)
                        </label>
                    @endif
                </div>
            @endif

            @if($k === 'email')
                <div class="space-y-4">
                    <h2 class="text-lg font-bold">📧 Email hosting</h2>
                    <p class="text-sm text-gray-500">Professional addresses for @{{ rtrim($domainPrefix,'.') }}{{ $domainExtension }}</p>
                    <div class="grid gap-3 md:grid-cols-3">
                        @foreach(['starter' => ['Starter',10],'business' => ['Business',24],'enterprise' => ['Enterprise',50]] as $id => $row)
                            <button type="button" wire:click="$set('emailPackage','{{ $id }}')"
                                    class="rounded-xl border p-4 text-left {{ $emailPackage === $id ? 'border-primary-600 ring-2 ring-primary-500/30' : 'border-gray-200 dark:border-gray-700' }}">
                                <div class="font-bold">{{ $row[0] }}</div>
                                <div class="text-primary-700 font-semibold">${{ $row[1] }}/yr</div>
                            </button>
                        @endforeach
                    </div>
                    <div class="space-y-2">
                        @foreach($emailAddresses as $i => $row)
                            <div class="flex gap-2">
                                <input wire:model="emailAddresses.{{ $i }}.prefix" class="fi-input rounded-lg border-gray-300 dark:bg-gray-900" placeholder="prefix"/>
                                <span class="self-center text-sm text-gray-500">@{{ rtrim($domainPrefix,'.') }}{{ $domainExtension }}</span>
                                <input wire:model="emailAddresses.{{ $i }}.purpose" class="fi-input rounded-lg border-gray-300 dark:bg-gray-900 w-40" placeholder="purpose"/>
                            </div>
                        @endforeach
                        <button type="button" wire:click="addEmailRow" class="text-sm text-primary-700 font-semibold">+ Add row</button>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        @foreach(['admin','accounts','orders','marketing'] as $chip)
                            <button type="button" class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-800" wire:click="$set('emailAddresses.' . (count($emailAddresses)-1) . '.prefix', '{{ $chip }}')">{{ $chip }}@</button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($k === 'company_reg')
                <div class="space-y-4">
                    <h2 class="text-lg font-bold">🏢 Company registration</h2>
                    <div class="space-y-2">
                        @foreach(['pvt_ltd' => 'Private (Pvt Ltd) — $120','pbc' => 'PBC — $100','trust' => 'Trust — $150','ngo' => 'NGO — $100'] as $tid => $lab)
                            <label class="flex items-center gap-2">
                                <input type="radio" wire:model="companyType" value="{{ $tid }}"/> {{ $lab }}
                            </label>
                        @endforeach
                    </div>
                    <p class="text-sm font-semibold">Name options</p>
                    @foreach([0,1,2] as $ni)
                        <input wire:model="companyNameOptions.{{ $ni }}" class="fi-input w-full rounded-lg border-gray-300 dark:bg-gray-900" placeholder="Suggested name {{ $ni+1 }}"/>
                    @endforeach
                    <p class="text-sm font-semibold">Directors</p>
                    @foreach($directors as $di => $dir)
                        <div class="grid gap-2 rounded-lg border border-gray-200 p-3 dark:border-gray-700 md:grid-cols-2">
                            <input wire:model="directors.{{ $di }}.name" placeholder="Full name" class="fi-input rounded-lg border-gray-300 dark:bg-gray-900"/>
                            <input wire:model="directors.{{ $di }}.id_number" placeholder="ID 00-000000-X-00" class="fi-input rounded-lg border-gray-300 dark:bg-gray-900"/>
                            <input wire:model="directors.{{ $di }}.address" placeholder="Address" class="fi-input rounded-lg border-gray-300 dark:bg-gray-900 md:col-span-2"/>
                            <input wire:model="directors.{{ $di }}.shares" placeholder="Share %" class="fi-input rounded-lg border-gray-300 dark:bg-gray-900"/>
                            <input wire:model="directors.{{ $di }}.nationality" placeholder="Nationality" class="fi-input rounded-lg border-gray-300 dark:bg-gray-900"/>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addDirector" class="text-sm font-semibold text-primary-700">+ Add director</button>
                    <div class="space-y-2 text-sm">
                        @foreach(['tax_clearance' => 'Tax clearance (+$50)','stamp' => 'Rubber stamp (+$30)','nssa' => 'NSSA (+$50)','bank_letter' => 'Bank letter (+$15)'] as $cid => $clab)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:click="toggleCompanyAddon('{{ $cid }}')" @checked(in_array($cid, $companyAddons, true))/>
                                {{ $clab }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($k === 'tax_clearance')
                <div class="space-y-4">
                    <h2 class="text-lg font-bold">💰 Tax clearance</h2>
                    <label class="flex items-center gap-2"><input type="radio" wire:model.live="taxUrgent" :value="0"/> Standard — $50</label>
                    <label class="flex items-center gap-2"><input type="radio" wire:model.live="taxUrgent" :value="1"/> Urgent — $80</label>
                    <p class="text-sm text-gray-500">Company details pre-filled from the customer record where available.</p>
                    <label class="flex items-center gap-2"><input type="radio" wire:model="taxFrequency" value="once"/> One-time</label>
                    <label class="flex items-center gap-2"><input type="radio" wire:model="taxFrequency" value="quarterly"/> Quarterly retainer</label>
                </div>
            @endif

            @if($k === 'business_plan')
                <div class="space-y-2">
                    <h2 class="text-lg font-bold">📑 Business plan</h2>
                    <p class="text-sm text-gray-600">Includes market overview, financial projections template, and investor-ready summary.</p>
                </div>
            @endif

            @if($k === 'paid_social')
                <div class="space-y-6">
                    <div class="relative overflow-hidden rounded-2xl border border-fuchsia-500/30 bg-gradient-to-br from-slate-900 via-violet-950/80 to-slate-900 p-5 text-white shadow-lg ring-1 ring-white/10">
                        <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-fuchsia-500/25 blur-2xl"></div>
                        <div class="pointer-events-none absolute -bottom-10 -left-10 h-36 w-36 rounded-full bg-cyan-500/20 blur-2xl"></div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-fuchsia-200/90">Flight deck</p>
                        <h2 class="mt-1 text-xl font-extrabold tracking-tight">Paid social — price the craft, <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-fuchsia-300">bill the platforms separately</span></h2>
                        <p class="mt-2 text-sm text-slate-300/90 leading-relaxed max-w-xl">
                            Meta, Instagram, and TikTok ad spend hits the client’s card or your pass-through account. Here you scope <strong>your</strong> setup, creative, and management—then pin the <strong>campaign end date &amp; time</strong> so renewals never ghost.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1.5">Campaign name</label>
                        <input wire:model="adsCampaignName" class="fi-input w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="e.g. Q2 lead gen — Summer promo"/>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1.5">End date</label>
                            <input type="date" wire:model="adsCampaignEndDate" class="fi-input w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1.5">End time (local)</label>
                            <input type="time" wire:model="adsCampaignEndTime" class="fi-input w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1.5">Timezone</label>
                        <select wire:model="adsTimezone" class="fi-input w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            <option value="Africa/Harare">Africa / Harare</option>
                            <option value="Africa/Johannesburg">Africa / Johannesburg</option>
                            <option value="UTC">UTC</option>
                            <option value="Europe/London">Europe / London</option>
                        </select>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">Management bundle</p>
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach([
                                'meta' => ['Meta + IG','Facebook & Instagram in Meta Ads Manager','from $450/mo'],
                                'tiktok' => ['TikTok','In-feed & Spark-style builds','from $420/mo'],
                                'bundle' => ['Full orbit','Meta + IG + TikTok','from $750/mo'],
                            ] as $bid => $row)
                                <button type="button" wire:click="$set('adsPlatformBundle','{{ $bid }}')"
                                        class="rounded-xl border p-4 text-left transition {{ $adsPlatformBundle === $bid ? 'border-fuchsia-500 ring-2 ring-fuchsia-500/30 bg-fuchsia-50/50 dark:bg-fuchsia-950/30' : 'border-gray-200 dark:border-gray-700 hover:border-fuchsia-400/60' }}">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-fuchsia-600 dark:text-fuchsia-300">{{ $row[0] }}</span>
                                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $row[1] }}</p>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ $row[2] }}</p>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-xl border border-dashed border-violet-300/60 bg-violet-50/50 dark:bg-violet-950/20 dark:border-violet-500/30 p-4">
                        <p class="text-xs font-bold text-violet-800 dark:text-violet-200">Platform charges (reference)</p>
                        <ul class="mt-2 space-y-1.5 text-xs text-violet-900/90 dark:text-violet-100/80 leading-relaxed">
                            <li><strong>Meta:</strong> ad spend billed to the ad account; optional agency markup (e.g. 10–15%) — spell it out on the quote.</li>
                            <li><strong>TikTok:</strong> spend bills to TikTok Ads; your lines here are management + creative.</li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">Boosters</p>
                        <div class="grid gap-2 sm:grid-cols-2 text-sm">
                            @foreach([
                                'landing_page' => 'Landing page / LP refresh (+$320)',
                                'competitor_report' => 'Competitor ad snapshot (+$90)',
                                'whatsapp_ads' => 'WhatsApp click-to-message setup (+$85)',
                                'rush_launch' => 'Rush launch — under 5 business days (+$200)',
                            ] as $aid => $alab)
                                <label class="flex items-center gap-2 rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <input type="checkbox" wire:click="togglePaidSocialAddon('{{ $aid }}')" @checked(in_array($aid, $adsAddons, true))/>
                                    <span>{{ $alab }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    @if($adsCampaignEndDate && $adsCampaignName)
                        <div class="flex items-center gap-3 rounded-xl border border-cyan-500/30 bg-cyan-50/80 dark:bg-cyan-950/30 dark:border-cyan-400/25 px-4 py-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-600 text-white text-lg font-black shrink-0" aria-hidden="true">⏱</div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-cyan-800 dark:text-cyan-200">Countdown anchor</p>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $adsCampaignName }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-300">Ends {{ $adsCampaignEndDate }} at {{ $adsCampaignEndTime }} ({{ $adsTimezone }}) — reminders at 72h, 24h, 2h.</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if($k === 'review')
                <div class="space-y-4">
                    <h2 class="text-lg font-bold">📋 Review for {{ $clientName }}</h2>
                    <div class="rounded-lg border border-gray-200 p-4 text-sm dark:border-gray-700 space-y-1">
                        <div class="flex justify-between"><span>Subtotal</span><strong>${{ number_format($pricing['subtotal'],2) }}</strong></div>
                        <div class="flex justify-between text-gray-500"><span>Your cost (internal)</span><span>${{ number_format($pricing['total_cost'],2) }}</span></div>
                        <div class="flex justify-between text-emerald-700"><span>Profit</span><strong>${{ number_format($pricing['profit'],2) }} ({{ $pricing['margin_percent'] ?? '—' }}%)</strong></div>
                    </div>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2"><input type="radio" wire:model="paymentTerms" value="deposit"/> 50% deposit / 50% on delivery</label>
                        <label class="flex items-center gap-2"><input type="radio" wire:model="paymentTerms" value="full"/> Full upfront (5% courtesy discount applied manually)</label>
                        <label class="flex items-center gap-2"><input type="radio" wire:model="paymentTerms" value="installments"/> Three installments</label>
                    </div>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center gap-2"><input type="checkbox" wire:model="sendEmail"/> Email PDF + portal link</label>
                        <label class="flex items-center gap-2"><input type="checkbox" wire:model="sendWhatsapp"/> WhatsApp link (logged)</label>
                        <label class="flex items-center gap-2"><input type="checkbox" wire:model="sendSms"/> SMS (optional)</label>
                    </div>
                    <textarea wire:model.blur="personalMessage" rows="3" class="fi-input w-full rounded-lg border-gray-300 dark:bg-gray-900" placeholder="Personal message to the client…"></textarea>
                    @if($orderId)
                        <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-600 dark:bg-slate-900/40">
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-600 dark:text-slate-400 mb-2">{{ __('Preview & export (saved draft)') }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-3">{{ __('PDF, CSV, and Excel use the last saved draft. Click “Save draft” after edits, then open a link.') }}</p>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('documents.order.quote-preview.print', ['order' => $orderId]) }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-800 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                                    {{ __('Print') }}
                                </a>
                                <a href="{{ route('documents.order.quote-preview.print', ['order' => $orderId]) }}?autoprint=1" target="_blank" rel="noopener"
                                   class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-800 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                                    {{ __('Print (auto)') }}
                                </a>
                                <a href="{{ route('documents.order.quote-preview.pdf', ['order' => $orderId]) }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-3 py-2 text-xs font-semibold text-white hover:bg-primary-500">
                                    {{ __('PDF') }}
                                </a>
                                <a href="{{ route('documents.order.quote-preview.csv', ['order' => $orderId]) }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-800 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                                    {{ __('CSV') }}
                                </a>
                                <a href="{{ route('documents.order.quote-preview.xlsx', ['order' => $orderId]) }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-800 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                                    {{ __('Excel') }}
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-amber-800 dark:text-amber-200/90">{{ __('Save a draft first to enable print, PDF, CSV, and Excel from your quote data.') }}</p>
                    @endif
                </div>
            @endif

            <div class="flex flex-wrap gap-3 border-t border-gray-100 pt-4 dark:border-gray-800">
                <button type="button" wire:click="back" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold dark:border-gray-600">← Back</button>
                <button type="button" wire:click="saveDraft" class="rounded-lg border border-dashed border-gray-400 px-4 py-2 text-sm font-semibold">Save draft</button>
                @if($k !== 'review')
                    <button type="button" wire:click="next" class="ml-auto rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-500">Continue →</button>
                @else
                    <button type="button" wire:click="sendQuote" class="ml-auto rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-500">Generate & send quote</button>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="lg:col-span-4 space-y-3 rounded-xl border border-gray-200 bg-gray-50/80 p-4 text-sm dark:border-gray-700 dark:bg-gray-900/40">
            <h3 class="font-bold text-gray-900 dark:text-white">Order summary</h3>
            <ul class="space-y-2">
                @foreach($pricing['lines'] as $line)
                    <li class="flex justify-between gap-2">
                        <span>{{ $line['name'] }}</span>
                        <span class="font-mono text-xs">${{ number_format($line['line_total'],2) }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="border-t border-gray-200 pt-2 dark:border-gray-700 space-y-1">
                <div class="flex justify-between font-semibold"><span>Subtotal</span><span>${{ number_format($pricing['subtotal'],2) }}</span></div>
                <div class="flex justify-between text-emerald-700"><span>Profit</span><span>${{ number_format($pricing['profit'],2) }} ({{ $pricing['margin_percent'] ?? '—' }}%)</span></div>
                <div class="text-xs text-gray-500">One-time: ${{ number_format($pricing['one_time'],2) }} · Recurring/yr: ${{ number_format($pricing['recurring_annual'],2) }}</div>
            </div>
        </aside>
    </div>
</div>
