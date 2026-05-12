<?php

namespace App\Services;

use App\Models\ComplianceAlert;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class ComplianceTracker
{
    /**
     * Create compliance alerts from finalized order wizard metadata.
     */
    public function syncFromOrder(Order $order): void
    {
        $state = $order->wizard_state ?? [];
        $clientId = (int) $order->client_id;
        if ($clientId === 0) {
            return;
        }

        $tenantId = (int) $order->tenant_id;

        if (in_array('domain', $state['selected_services'] ?? [], true)) {
            $d = $state['domain'] ?? [];
            $expiry = isset($d['expiry_date']) ? Carbon::parse($d['expiry_date']) : now()->addYears((int) ($d['period_years'] ?? 3));
            foreach ([90, 30, 7] as $days) {
                $this->createAlert($tenantId, $clientId, 'domain_renewal', $expiry->copy()->subDays($days), $expiry, 'Domain renewal: '.trim((string) ($d['prefix'] ?? ''), '.').($d['extension'] ?? ''));
            }
        }

        if (in_array('email', $state['selected_services'] ?? [], true)) {
            $e = $state['email'] ?? [];
            $expiry = isset($e['expiry_date']) ? Carbon::parse($e['expiry_date']) : now()->addYear();
            foreach ([90, 30, 7] as $days) {
                $this->createAlert($tenantId, $clientId, 'email_renewal', $expiry->copy()->subDays($days), $expiry, 'Email hosting renewal');
            }
        }

        if (in_array('company_reg', $state['selected_services'] ?? [], true)) {
            $this->createAlert($tenantId, $clientId, 'company_rereg_10y', now()->addYears(10), null, 'Company re-registration (10 years)');
            $this->createAlert($tenantId, $clientId, 'annual_return', now()->addYear(), null, 'Annual return filing');
        }

        if (in_array('tax_clearance', $state['selected_services'] ?? [], true)) {
            $this->createAlert($tenantId, $clientId, 'tax_clearance_expiry', now()->addDays(80), now()->addDays(90), 'Tax clearance renewal window');
        }
    }

    protected function createAlert(int $tenantId, int $clientId, string $type, CarbonInterface $alertDate, ?CarbonInterface $expiry, string $label): void
    {
        ComplianceAlert::query()->firstOrCreate(
            [
                'tenant_id' => $tenantId,
                'client_id' => $clientId,
                'alert_type' => $type.'_'.$alertDate->toDateString(),
            ],
            [
                'document_id' => null,
                'alert_date' => $alertDate->toDateString(),
                'expiry_date' => $expiry?->toDateString(),
                'status' => 'upcoming',
            ]
        );
    }
}
