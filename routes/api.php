<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CashflowEntryController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\DealController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\QuoteController;
use App\Http\Controllers\Api\V1\ServiceTemplateController;
use App\Http\Controllers\Api\V1\TenantNotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->group(function () {
        Route::get('/health', fn () => response()->json(['ok' => true]));

        Route::middleware(['throttle:10,1'])->group(function () {
            Route::post('/auth/login', [AuthController::class, 'login']);
        });

        Route::middleware(['throttle:30,1'])->group(function () {
            Route::get('quotes/public/{token}', [QuoteController::class, 'publicShow']);
        });

        Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
            Route::post('/auth/logout', [AuthController::class, 'logout']);
            Route::post('/auth/refresh', [AuthController::class, 'refresh']);
            Route::get('/auth/me', [AuthController::class, 'me']);
            Route::put('/auth/profile', [AuthController::class, 'profile']);
            Route::put('/auth/password', [AuthController::class, 'password']);

            Route::get('/dashboard', [DashboardController::class, 'show']);

            Route::get('/notifications', [TenantNotificationController::class, 'index']);
            Route::post('/notifications/{id}/read', [TenantNotificationController::class, 'markRead']);

            Route::apiResource('clients', ClientController::class);

            Route::apiResource('documents', DocumentController::class);

            Route::get('service-templates', [ServiceTemplateController::class, 'index']);
            Route::get('service-templates/{service_template}', [ServiceTemplateController::class, 'show']);
            Route::middleware('plan:service_templates_manage')->group(function () {
                Route::post('service-templates', [ServiceTemplateController::class, 'store']);
                Route::put('service-templates/{service_template}', [ServiceTemplateController::class, 'update']);
                Route::delete('service-templates/{service_template}', [ServiceTemplateController::class, 'destroy']);
            });

            Route::middleware('plan:quotes')->group(function () {
                Route::apiResource('quotes', QuoteController::class);
                Route::post('quotes/{quote}/send', [QuoteController::class, 'send']);
                Route::post('quotes/{quote}/convert-invoice', [QuoteController::class, 'convertToInvoice']);
                Route::get('quotes/{quote}/pdf', [QuoteController::class, 'pdf'])->middleware('plan:pdf');
            });

            Route::apiResource('invoices', InvoiceController::class);
            Route::post('invoices/{invoice}/payments', [InvoiceController::class, 'recordPayment']);
            Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->middleware('plan:pdf');

            Route::middleware('plan:deals')->group(function () {
                Route::apiResource('deals', DealController::class);
            });

            Route::middleware('plan:cashflow')->group(function () {
                Route::apiResource('cashflow-entries', CashflowEntryController::class);
            });
        });
    });
