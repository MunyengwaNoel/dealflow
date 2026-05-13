<?php

namespace App\Services;

use App\Enums\OrderServiceType;

/**
 * Server-side totals for the order wizard sidebar (no client-side arithmetic).
 *
 * @phpstan-type LineInput array{service_type?: string, name: string, unit_price: float|int|string, unit_cost?: float|int|string, quantity?: float|int|string, status?: string, recurring?: bool}
 */
class PriceCalculator
{
    /**
     * @param  list<LineInput>  $lines
     * @return array{lines: list<array{service_type: string, name: string, unit_price: float, unit_cost: float, quantity: float, line_total: float, line_profit: float, status: string, recurring: bool}>, subtotal: float, total_cost: float, profit: float, margin_percent: float|null, one_time: float, recurring_annual: float}
     */
    public function compute(array $lines): array
    {
        $normalized = [];
        $subtotal = 0.0;
        $totalCost = 0.0;
        $oneTime = 0.0;
        $recurringAnnual = 0.0;

        foreach ($lines as $line) {
            $qty = (float) ($line['quantity'] ?? 1);
            $unitPrice = (float) ($line['unit_price'] ?? 0);
            $unitCost = (float) ($line['unit_cost'] ?? 0);
            $lineTotal = round($unitPrice * $qty, 2);
            $lineProfit = round(($unitPrice - $unitCost) * $qty, 2);
            $recurring = (bool) ($line['recurring'] ?? false);

            $normalized[] = [
                'service_type' => (string) ($line['service_type'] ?? 'custom'),
                'name' => (string) ($line['name'] ?? 'Line item'),
                'unit_price' => $unitPrice,
                'unit_cost' => $unitCost,
                'quantity' => $qty,
                'line_total' => $lineTotal,
                'line_profit' => $lineProfit,
                'status' => (string) ($line['status'] ?? 'pending'),
                'recurring' => $recurring,
            ];

            $subtotal += $lineTotal;
            $totalCost += round($unitCost * $qty, 2);
            if ($recurring) {
                $recurringAnnual += $lineTotal;
            } else {
                $oneTime += $lineTotal;
            }
        }

        $profit = round($subtotal - $totalCost, 2);
        $margin = $subtotal > 0 ? round(($profit / $subtotal) * 100, 2) : null;

        return [
            'lines' => $normalized,
            'subtotal' => round($subtotal, 2),
            'total_cost' => round($totalCost, 2),
            'profit' => $profit,
            'margin_percent' => $margin,
            'one_time' => round($oneTime, 2),
            'recurring_annual' => round($recurringAnnual, 2),
        ];
    }

    /**
     * @param  array<string, mixed>  $wizardState
     * @return list<LineInput>
     */
    public function linesFromWizardState(array $wizardState): array
    {
        $lines = [];
        $services = $wizardState['selected_services'] ?? [];

        if (in_array('domain', $services, true)) {
            $d = $wizardState['domain'] ?? [];
            $fqdn = trim((string) ($d['prefix'] ?? ''), '.').($d['extension'] ?? '.co.zw');
            $years = (int) ($d['period_years'] ?? 3);
            $basePerYear = match ($d['extension'] ?? '.co.zw') {
                '.com', '.net' => 12.0,
                '.org' => 15.0,
                '.africa' => 25.0,
                default => 3.0,
            };
            $privacy = ! empty($d['privacy']) ? 5.0 * $years : 0.0;
            $sell = round($basePerYear * $years + $privacy, 2);
            $cost = round($sell * 0.45, 2);
            $lines[] = [
                'service_type' => OrderServiceType::Domain->value,
                'name' => ! empty(trim((string) ($d['prefix'] ?? ''), '.')) ? 'Domain: '.$fqdn : 'Domain registration',
                'unit_price' => $sell,
                'unit_cost' => $cost,
                'quantity' => 1,
                'status' => ! empty($d['availability_checked']) ? 'in_progress' : 'pending',
                'recurring' => false,
            ];
        }

        if (in_array('website', $services, true)) {
            $w = $wizardState['website'] ?? [];
            $pkg = (string) ($w['package'] ?? 'basic');
            $base = match ($pkg) {
                'business' => 350.0,
                'ecommerce' => 800.0,
                default => 150.0,
            };
            $cost = match ($pkg) {
                'business' => 120.0,
                'ecommerce' => 300.0,
                default => 50.0,
            };
            $addons = $w['addons'] ?? [];
            $addonSell = 0.0;
            $addonCost = 0.0;
            foreach ((array) $addons as $a) {
                switch ($a) {
                    case 'live_chat':
                        $addonSell += 50.0;
                        $addonCost += 20.0;
                        break;
                    case 'custom_design':
                        $addonSell += 150.0;
                        $addonCost += 80.0;
                        break;
                    case 'booking':
                        $addonSell += 100.0;
                        $addonCost += 40.0;
                        break;
                    case 'maintenance':
                        $addonSell += 360.0;
                        $addonCost += 144.0;
                        break;
                    case 'content_write':
                        $addonSell += 200.0;
                        $addonCost += 80.0;
                        break;
                }
            }
            if (! empty($w['hosting_auto'])) {
                $addonSell += 30.0;
                $addonCost += 15.0;
            }
            $lines[] = [
                'service_type' => OrderServiceType::Website->value,
                'name' => 'Website ('.$pkg.')',
                'unit_price' => round($base + $addonSell, 2),
                'unit_cost' => round($cost + $addonCost, 2),
                'quantity' => 1,
                'status' => $pkg !== '' ? 'in_progress' : 'pending',
                'recurring' => in_array('maintenance', (array) $addons, true),
            ];
        }

        if (in_array('email', $services, true)) {
            $e = $wizardState['email'] ?? [];
            $pkg = (string) ($e['package'] ?? 'starter');
            $base = match ($pkg) {
                'business' => 24.0,
                'enterprise' => 50.0,
                default => 10.0,
            };
            $cost = $base * 0.5;
            $extra = max(0, count($e['addresses'] ?? []) - match ($pkg) {
                'business' => 5,
                'enterprise' => 10,
                default => 3,
            });
            $extraSell = $extra * 3.0;
            $lines[] = [
                'service_type' => OrderServiceType::Email->value,
                'name' => 'Email hosting ('.$pkg.')',
                'unit_price' => round($base + $extraSell, 2),
                'unit_cost' => round($cost + $extra * 1.5, 2),
                'quantity' => 1,
                'status' => 'pending',
                'recurring' => true,
            ];
        }

        if (in_array('company_reg', $services, true)) {
            $c = $wizardState['company_reg'] ?? [];
            $type = (string) ($c['company_type'] ?? 'pvt_ltd');
            $sell = match ($type) {
                'pbc' => 100.0,
                'trust' => 150.0,
                'ngo' => 100.0,
                default => 120.0,
            };
            $cost = $sell * 0.55;
            $addons = $c['addons'] ?? [];
            foreach ((array) $addons as $a) {
                switch ($a) {
                    case 'tax_clearance':
                        $sell += 50.0;
                        $cost += 25.0;
                        break;
                    case 'stamp':
                        $sell += 30.0;
                        $cost += 12.0;
                        break;
                    case 'nssa':
                        $sell += 50.0;
                        $cost += 25.0;
                        break;
                    case 'bank_letter':
                        $sell += 15.0;
                        $cost += 5.0;
                        break;
                }
            }
            $lines[] = [
                'service_type' => OrderServiceType::CompanyReg->value,
                'name' => 'Company registration',
                'unit_price' => round($sell, 2),
                'unit_cost' => round($cost, 2),
                'quantity' => 1,
                'status' => 'pending',
                'recurring' => false,
            ];
        }

        if (in_array('tax_clearance', $services, true)) {
            $t = $wizardState['tax_clearance'] ?? [];
            $urgent = ! empty($t['urgent']);
            $sell = $urgent ? 80.0 : 50.0;
            $lines[] = [
                'service_type' => OrderServiceType::TaxClearance->value,
                'name' => $urgent ? 'Tax clearance (urgent)' : 'Tax clearance',
                'unit_price' => $sell,
                'unit_cost' => round($sell * 0.5, 2),
                'quantity' => 1,
                'status' => 'pending',
                'recurring' => (($t['frequency'] ?? 'once') === 'quarterly'),
            ];
        }

        if (in_array('business_plan', $services, true)) {
            $lines[] = [
                'service_type' => OrderServiceType::BusinessPlan->value,
                'name' => 'Business plan',
                'unit_price' => 200.0,
                'unit_cost' => 90.0,
                'quantity' => 1,
                'status' => 'pending',
                'recurring' => false,
            ];
        }

        if (in_array('paid_social', $services, true)) {
            $p = $wizardState['paid_social'] ?? [];
            $bundle = (string) ($p['platform_bundle'] ?? 'meta');
            [$monthlySell, $monthlyCost] = match ($bundle) {
                'tiktok' => [420.0, 155.0],
                'bundle' => [750.0, 260.0],
                default => [450.0, 160.0],
            };
            $setupSell = 280.0;
            $setupCost = 120.0;
            $addonSell = 0.0;
            $addonCost = 0.0;
            foreach ((array) ($p['addons'] ?? []) as $a) {
                switch ($a) {
                    case 'landing_page':
                        $addonSell += 320.0;
                        $addonCost += 120.0;
                        break;
                    case 'competitor_report':
                        $addonSell += 90.0;
                        $addonCost += 30.0;
                        break;
                    case 'whatsapp_ads':
                        $addonSell += 85.0;
                        $addonCost += 30.0;
                        break;
                    case 'rush_launch':
                        $addonSell += 200.0;
                        $addonCost += 80.0;
                        break;
                }
            }
            $bundleLabel = match ($bundle) {
                'tiktok' => 'TikTok Ads',
                'bundle' => 'Meta + Instagram + TikTok',
                default => 'Meta (Facebook + Instagram)',
            };
            $camp = trim((string) ($p['campaign_name'] ?? ''));
            $end = (string) ($p['campaign_end_date'] ?? '');
            $tm = (string) ($p['campaign_end_time'] ?? '');
            $tz = (string) ($p['timezone'] ?? '');
            $suffix = $camp !== '' && $end !== '' ? ' — '.$camp.' · ends '.$end.' '.$tm.' ('.$tz.')' : '';

            $lines[] = [
                'service_type' => OrderServiceType::PaidSocial->value,
                'name' => 'Paid social: setup & launch'.$suffix,
                'unit_price' => round($setupSell + $addonSell, 2),
                'unit_cost' => round($setupCost + $addonCost, 2),
                'quantity' => 1,
                'status' => 'pending',
                'recurring' => false,
            ];
            $lines[] = [
                'service_type' => OrderServiceType::PaidSocial->value,
                'name' => 'Paid social: monthly management ('.$bundleLabel.')',
                'unit_price' => round($monthlySell, 2),
                'unit_cost' => round($monthlyCost, 2),
                'quantity' => 1,
                'status' => 'pending',
                'recurring' => true,
            ];
        }

        return $lines;
    }
}
