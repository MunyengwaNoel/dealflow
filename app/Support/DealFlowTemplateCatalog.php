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
}
