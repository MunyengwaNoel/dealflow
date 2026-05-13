<?php

namespace App\Support;

/**
 * Default DealFlow Pro service catalog (FR 4.1.x). Seeded per tenant.
 */
class DealFlowTemplateCatalog
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function seedRows(): array
    {
        return [
            self::websitePackage(),
            self::companyRegistration(),
            self::domainHosting(),
            self::taxCompliance(),
            self::digitalMarketingPaidSocial(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function websitePackage(): array
    {
        return [
            'template_code' => 'SRV-WEB-001',
            'name' => 'Website Development Package',
            'category' => 'website',
            'description' => 'Template-driven website packages with tiers, add-ons, hosting, and domain options.',
            'cost_price' => '50.00',
            'sell_price' => '150.00',
            'timeline_days' => 14,
            'version_label' => '1.0',
            'pricing_structure' => [
                'currency' => 'USD',
                'tiers' => [
                    ['id' => 'basic', 'name' => 'Basic Website', 'sell_price' => 150, 'cost_price' => 50, 'profit' => 100, 'demo_url' => 'https://basic.demo.co.zw', 'features' => ['5 pages', 'Mobile responsive', 'Contact form', 'Social media integration']],
                    ['id' => 'business', 'name' => 'Business Website', 'sell_price' => 350, 'cost_price' => 120, 'profit' => 230, 'demo_url' => 'https://business.demo.co.zw', 'features' => ['All Basic features', 'Blog/News', 'Photo gallery', 'Google Maps', 'SEO optimization']],
                    ['id' => 'ecommerce', 'name' => 'E-commerce Website', 'sell_price' => 800, 'cost_price' => 300, 'profit' => 500, 'demo_url' => 'https://shop.demo.co.zw', 'features' => ['All Business features', 'Product catalog (50)', 'Shopping cart', 'Payment gateway', 'Order management']],
                ],
                'addons' => [
                    ['name' => 'Live chat integration', 'sell_price' => 50, 'cost_price' => 20],
                    ['name' => 'Customer login portal', 'sell_price' => 100, 'cost_price' => 40],
                    ['name' => 'Custom design (non-template)', 'sell_price' => 150, 'cost_price' => 80],
                    ['name' => 'Multilingual (+1 language)', 'sell_price' => 80, 'cost_price' => 35],
                    ['name' => 'Monthly maintenance', 'sell_price' => 30, 'cost_price' => 12, 'billing' => 'per_month'],
                    ['name' => 'Logo design', 'sell_price' => 20, 'cost_price' => 8],
                ],
                'domain_options' => [
                    ['tld' => '.co.zw', 'sell_price' => 3, 'cost_price' => 2],
                    ['tld' => '.com', 'sell_price' => 12, 'cost_price' => 8],
                    ['tld' => '.africa', 'sell_price' => 25, 'cost_price' => 18],
                    ['tld' => '.co.za', 'sell_price' => 15, 'cost_price' => 10],
                ],
                'hosting' => [
                    ['id' => 'web_hosting', 'name' => 'Web Hosting (annual)', 'sell_price' => 30, 'cost_price' => 15],
                    ['id' => 'email_hosting', 'name' => 'Email Hosting (5 addresses)', 'sell_price' => 24, 'cost_price' => 12],
                ],
            ],
            'demo_links' => [
                'https://basic.demo.co.zw',
                'https://business.demo.co.zw',
                'https://shop.demo.co.zw',
            ],
            'required_documents' => [
                ['label' => 'Company logo (or request logo add-on)', 'mandatory' => false],
                ['label' => 'Company profile / bio', 'mandatory' => true],
                ['label' => 'Photos (team, products, office)', 'mandatory' => false],
                ['label' => 'Domain preference', 'mandatory' => true],
                ['label' => 'Colour scheme preference', 'mandatory' => false],
            ],
            'deliverables' => [
                'Fully functional website',
                'Admin panel access',
                'Training video (20 mins)',
                '30-day support',
                'SSL certificate',
            ],
            'automation_rules' => [
                'on_deal_won' => ['create_tasks' => ['Collect required documents', 'Schedule kickoff']],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function companyRegistration(): array
    {
        return [
            'template_code' => 'SRV-REG-001',
            'name' => 'Company Registration Services',
            'category' => 'registration',
            'description' => 'Private company, PBC, and re-registration packages with add-ons and document checklist.',
            'cost_price' => '65.00',
            'sell_price' => '120.00',
            'timeline_days' => 7,
            'version_label' => '1.0',
            'pricing_structure' => [
                'currency' => 'USD',
                'tiers' => [
                    ['id' => 'pvt_ltd', 'name' => 'Private Company (Pvt Ltd)', 'sell_price' => 120, 'cost_price' => 65, 'profit' => 55, 'timeline_days' => 7, 'sample_doc' => 'view-sample-cr14.pdf'],
                    ['id' => 'pbc', 'name' => 'Private Business Corporation (PBC)', 'sell_price' => 100, 'cost_price' => 50, 'profit' => 50, 'timeline_days' => 7],
                    ['id' => 'rereg', 'name' => 'Company Re-Registration', 'sell_price' => 80, 'cost_price' => 40, 'profit' => 40, 'timeline_days' => 5],
                ],
                'addons' => [
                    ['name' => 'Tax Clearance Certificate', 'sell_price' => 50, 'cost_price' => 25],
                    ['name' => 'NSSA Registration', 'sell_price' => 50, 'cost_price' => 25],
                    ['name' => 'Company rubber stamp', 'sell_price' => 30, 'cost_price' => 12],
                    ['name' => 'Certified copies (set of 5)', 'sell_price' => 20, 'cost_price' => 8],
                    ['name' => 'Business bank account opening letter', 'sell_price' => 15, 'cost_price' => 5],
                    ['name' => 'Annual Return (AR1 form)', 'sell_price' => 50, 'cost_price' => 25],
                ],
            ],
            'demo_links' => [],
            'required_documents' => [
                ['label' => 'National ID copies (certified)', 'mandatory' => true],
                ['label' => 'Proof of residence', 'mandatory' => true],
                ['label' => 'Passport photos (2 each director)', 'mandatory' => true],
                ['label' => 'Proposed company names (3 options)', 'mandatory' => true],
                ['label' => 'Nature of business description', 'mandatory' => true],
                ['label' => 'Registered office address', 'mandatory' => true],
                ['label' => 'Share capital allocation', 'mandatory' => true],
            ],
            'deliverables' => [
                'Name reservation',
                'Memorandum & Articles preparation',
                'CR14 form completion',
                'Certificate of Incorporation',
                'Company profile document',
                'Compliance calendar (first year)',
            ],
            'automation_rules' => [
                'on_deal_won' => ['schedule' => ['compliance_calendar', 'rereg_reminder_10y']],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function domainHosting(): array
    {
        return [
            'template_code' => 'SRV-DOM-001',
            'name' => 'Domain & Hosting Services',
            'category' => 'domain',
            'description' => 'Domain extensions, privacy, transfers, hosting bundles, and renewal-friendly pricing.',
            'cost_price' => '15.00',
            'sell_price' => '30.00',
            'timeline_days' => 1,
            'version_label' => '1.0',
            'pricing_structure' => [
                'currency' => 'USD',
                'extensions' => [
                    ['tld' => '.co.zw', 'sell_price' => 3, 'cost_price' => 2],
                    ['tld' => '.com', 'sell_price' => 12, 'cost_price' => 8],
                    ['tld' => '.net', 'sell_price' => 12, 'cost_price' => 8],
                    ['tld' => '.org', 'sell_price' => 15, 'cost_price' => 10],
                    ['tld' => '.co.za', 'sell_price' => 15, 'cost_price' => 10],
                    ['tld' => '.africa', 'sell_price' => 25, 'cost_price' => 18],
                    ['tld' => '.org.zw', 'sell_price' => 5, 'cost_price' => 3],
                    ['tld' => '.ac.zw', 'sell_price' => 5, 'cost_price' => 3],
                ],
                'addons' => [
                    ['name' => 'Domain privacy', 'sell_price' => 5, 'cost_price' => 2, 'billing' => 'per_year'],
                    ['name' => 'Domain transfer', 'sell_price' => 10, 'cost_price' => 5, 'billing' => 'one_time'],
                ],
                'hosting_packages' => [
                    ['id' => 'cpanel', 'name' => 'Web Hosting (cPanel)', 'sell_price' => 30, 'cost_price' => 15, 'demo_url' => 'https://cpanel-demo.demo.co.zw'],
                    ['id' => 'email_only', 'name' => 'Email Hosting Only', 'sell_price' => 24, 'cost_price' => 12],
                ],
                'bundles' => [
                    ['id' => 'dom_web', 'name' => 'Domain + Web Hosting', 'sell_price' => 40, 'savings' => 8],
                    ['id' => 'dom_email', 'name' => 'Domain + Email Hosting', 'sell_price' => 30, 'savings' => 6],
                    ['id' => 'complete', 'name' => 'Domain + Web + Email', 'sell_price' => 55, 'savings' => 12],
                ],
            ],
            'demo_links' => [
                'https://cpanel-demo.demo.co.zw',
            ],
            'required_documents' => [
                ['label' => 'Desired domain names (up to 10 for bulk check)', 'mandatory' => true],
                ['label' => 'Registrant / company details', 'mandatory' => true],
            ],
            'deliverables' => [
                'DNS configuration',
                'Email forwarding setup',
                'SSL (Let\'s Encrypt)',
                '24/7 DNS management portal',
            ],
            'automation_rules' => [
                'on_renewal' => ['create_compliance_alert' => 'domain_expiry'],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function taxCompliance(): array
    {
        return [
            'template_code' => 'SRV-TAX-001',
            'name' => 'Tax Clearance & Compliance Services',
            'category' => 'tax',
            'description' => 'Tax clearance, annual returns, and bundled compliance retainers.',
            'cost_price' => '25.00',
            'sell_price' => '50.00',
            'timeline_days' => 5,
            'version_label' => '1.0',
            'pricing_structure' => [
                'currency' => 'USD',
                'tiers' => [
                    ['id' => 'tax_clearance', 'name' => 'Standard Tax Clearance', 'sell_price' => 50, 'cost_price' => 25, 'profit' => 25, 'timeline_days' => 5, 'sample_doc' => 'view-sample-taxclearance.pdf'],
                    ['id' => 'ar1', 'name' => 'Annual Return (AR1)', 'sell_price' => 80, 'cost_price' => 40, 'profit' => 40, 'timeline_days' => 7],
                ],
                'bundles' => [
                    ['name' => 'Quarterly tax clearance ×4', 'sell_price' => 180, 'savings' => 20],
                    ['name' => 'Annual Compliance (AR1 + Tax Clearance)', 'sell_price' => 120, 'savings' => 10],
                    ['name' => 'Full compliance + reminders', 'sell_price' => 250, 'savings' => 0],
                ],
            ],
            'demo_links' => [],
            'required_documents' => [
                ['label' => 'Latest financial statements', 'mandatory' => false],
                ['label' => 'Tax returns (if applicable)', 'mandatory' => false],
                ['label' => 'BP number', 'mandatory' => true],
                ['label' => 'TIN certificate', 'mandatory' => true],
                ['label' => 'Company registration documents', 'mandatory' => true],
            ],
            'deliverables' => [
                'ZIMRA submission & follow-up',
                'Certificate collection',
                'Digital PDF copy',
                'Certified hard copy (where applicable)',
            ],
            'automation_rules' => [
                'on_deal_won' => ['generate' => 'compliance_calendar'],
            ],
        ];
    }

    /**
     * Paid social / performance marketing — Meta (Facebook & Instagram), TikTok, etc.
     * Includes campaign end date & time, platform fee notes, and agency pricing scaffolding.
     *
     * @return array<string, mixed>
     */
    protected static function digitalMarketingPaidSocial(): array
    {
        return [
            'template_code' => 'SRV-ADS-001',
            'name' => 'Digital Marketing — Paid Social (Meta, Instagram, TikTok)',
            'category' => 'digital_marketing',
            'description' => 'Agency template for clients running paid ads on Facebook, Instagram, TikTok, and similar platforms. Capture campaign end date and time for renewals and reporting; separate your management fee from platform ad spend and pass-through charges.',
            'cost_price' => '120.00',
            'sell_price' => '280.00',
            'timeline_days' => 30,
            'version_label' => '1.0',
            'pricing_structure' => [
                'currency' => 'USD',
                'notes' => 'Ad spend is billed by the platform (or invoiced to you for pass-through). Replace example sell/cost figures with your live rate card. Always record campaign expiry date AND time (timezone-aware) per client.',
                'campaign_expiry' => [
                    'track_end_datetime' => true,
                    'timezone' => 'Africa/Harare',
                    'fields' => [
                        ['key' => 'campaign_name', 'label' => 'Campaign / flight name', 'required' => true],
                        ['key' => 'end_date', 'label' => 'Campaign end date', 'required' => true],
                        ['key' => 'end_time', 'label' => 'Campaign end time (local)', 'required' => true],
                        ['key' => 'timezone', 'label' => 'Timezone for end time', 'required' => true],
                        ['key' => 'renewal_intent', 'label' => 'Renew / pause / end at expiry', 'required' => false],
                    ],
                    'reminder_offsets_hours_before_end' => [72, 24, 2],
                ],
                'agency_core_fees' => [
                    ['id' => 'setup', 'name' => 'Account setup, pixel/conversion API, audiences', 'sell_price' => 280, 'cost_price' => 120, 'billing' => 'one_time'],
                    ['id' => 'mgmt_meta', 'name' => 'Monthly management — Meta (Facebook + Instagram)', 'sell_price' => 450, 'cost_price' => 160, 'billing' => 'per_month'],
                    ['id' => 'mgmt_tiktok', 'name' => 'Monthly management — TikTok Ads', 'sell_price' => 420, 'cost_price' => 155, 'billing' => 'per_month'],
                    ['id' => 'mgmt_bundle', 'name' => 'Monthly management — Meta + TikTok bundle', 'sell_price' => 750, 'cost_price' => 260, 'billing' => 'per_month'],
                ],
                'meta_facebook_instagram' => [
                    'platform' => 'Meta (Facebook & Instagram)',
                    'ads_manager' => 'Meta Ads Manager',
                    'platform_charges' => 'Meta charges ad spend to the ad account (card or invoicing where available). Optional taxes/fees may appear on Meta invoices. Typical agency models: (1) client pays Meta directly + you bill management only, or (2) you invoice ad spend + agreed markup (e.g. 10–15%) — document which model applies on the quote.',
                    'example_line_items' => [
                        ['label' => 'Creative pack — static + carousel (per campaign)', 'sell_price' => 180, 'cost_price' => 65],
                        ['label' => 'Monthly reporting & optimisation calls', 'sell_price' => 95, 'cost_price' => 35],
                        ['label' => 'Lead form / instant form build & QA', 'sell_price' => 120, 'cost_price' => 45],
                    ],
                ],
                'tiktok' => [
                    'platform' => 'TikTok Ads',
                    'platform_charges' => 'TikTok bills ad spend to the TikTok ad account. Your line items below are agency fees; keep ad spend separate on quotes for transparency.',
                    'example_line_items' => [
                        ['label' => 'Spark Ads / UGC briefing & shot list', 'sell_price' => 150, 'cost_price' => 55],
                        ['label' => 'In-feed video campaign build (3–5 ads)', 'sell_price' => 220, 'cost_price' => 85],
                    ],
                ],
                'addons' => [
                    ['name' => 'Landing page or LP refresh (conversion-focused)', 'sell_price' => 320, 'cost_price' => 120, 'billing' => 'one_time'],
                    ['name' => 'Competitor ad snapshot report', 'sell_price' => 90, 'cost_price' => 30, 'billing' => 'one_time'],
                    ['name' => 'WhatsApp / click-to-message ad setup (Meta)', 'sell_price' => 85, 'cost_price' => 30, 'billing' => 'one_time'],
                    ['name' => 'Rush launch (under 5 business days)', 'sell_price' => 200, 'cost_price' => 80, 'billing' => 'one_time'],
                ],
            ],
            'demo_links' => [],
            'required_documents' => [
                ['label' => 'Business Manager / ad account access (admin or advertiser)', 'mandatory' => true],
                ['label' => 'Brand guidelines & logo pack', 'mandatory' => true],
                ['label' => 'Approved offer, landing URL, and compliance disclaimers', 'mandatory' => true],
                ['label' => 'Target locations, languages, and exclusions', 'mandatory' => true],
                ['label' => 'Monthly ad spend budget cap (per platform)', 'mandatory' => true],
            ],
            'deliverables' => [
                'Media plan aligned to objectives (awareness / traffic / leads / sales)',
                'Campaign build with naming convention and UTM structure',
                'Campaign end date & time recorded per flight (with timezone)',
                'Weekly optimisation checklist during live period',
                'End-of-campaign report (spend, CPA/ROAS, learnings, renewal recommendation)',
                'Optional: calendar reminders before campaign expiry (e.g. 72h / 24h / 2h)',
            ],
            'automation_rules' => [
                'on_deal_won' => ['create_tasks' => ['Confirm ad account access', 'Lock campaign end date/time in CRM', 'Schedule pre-expiry reminders']],
                'on_renewal' => ['suggested_hook' => 'campaign_expiry_datetime'],
            ],
        ];
    }
}
