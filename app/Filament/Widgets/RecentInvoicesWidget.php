<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentInvoicesWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Invoices';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Invoice::query()
                    ->with('client')
                    ->latest()
                    ->limit(6)
            )
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->weight('semibold')
                    ->color('primary')
                    ->searchable(false),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable(false)
                    ->description(fn (Invoice $r): string => $r->client?->email ?? ''),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Amount')
                    ->money('USD')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_due')
                    ->label('Due')
                    ->money('USD')
                    ->color(fn (Invoice $r) => $r->amount_due > 0 ? 'danger' : 'success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid'    => 'success',
                        'sent'    => 'warning',
                        'partial' => 'warning',
                        'overdue' => 'danger',
                        default   => 'gray',
                    }),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('M j, Y')
                    ->color(fn (Invoice $r) => $r->due_date?->isPast() && $r->amount_due > 0
                        ? 'danger' : 'gray')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Invoice $r) => route('filament.admin.resources.invoices.edit', $r))
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->iconButton(),
            ])
            ->paginated(false)
            ->striped(false);
    }
}
