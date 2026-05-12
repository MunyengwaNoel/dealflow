<?php

namespace App\Enums;

enum OrderServiceType: string
{
    case Domain = 'domain';
    case Website = 'website';
    case Email = 'email';
    case CompanyReg = 'company_reg';
    case TaxClearance = 'tax_clearance';
    case BusinessPlan = 'business_plan';
}
