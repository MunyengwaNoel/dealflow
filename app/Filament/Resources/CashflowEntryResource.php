<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\DemoReadOnlyResource;
use App\Filament\Resources\CashflowEntryResource\Pages;
use App\Filament\Resources\CashflowEntryResource\Widgets\CashflowTotalsOverview;
use App\Models\CashflowEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CashflowEntryResource extends Resource
{
    use DemoReadOnlyResource;

    protected static ?string $model = CashflowEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 10;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['client', 'invoice', 'recordedBy']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('entry_type')
                    ->label(__('Entry type'))
                    ->options([
                        'income' => __('Income'),
                        'expense' => __('Expense'),
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('category')
                    ->label(__('Category'))
                    ->helperText(__('e.g. Tax, Sales, Company registration — free text'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('payment_method')
                    ->label(__('Payment method'))
                    ->options([
                        'cash' => __('Cash'),
                        'ecocash' => __('EcoCash'),
                        'zipit' => __('ZIPIT'),
                        'bank_transfer' => __('Bank transfer'),
                        'other' => __('Other'),
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\DatePicker::make('entry_date')
                    ->required(),
                Forms\Components\TextInput::make('reference')
                    ->maxLength(255),
                Forms\Components\Select::make('client_id')
                    ->label(__('Client'))
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\Select::make('invoice_id')
                    ->label(__('Invoice'))
                    ->relationship('invoice', 'invoice_number')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entry_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'income' => __('Income'),
                        'expense' => __('Expense'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'income' => 'success',
                        'expense' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('income_amount')
                    ->label(__('Income'))
                    ->state(fn (CashflowEntry $record): float => $record->entry_type === 'income' ? (float) $record->amount : 0.0)
                    ->numeric(decimalPlaces: 2)
                    ->summarize(Sum::make()->label(__('Income total'))),
                Tables\Columns\TextColumn::make('expense_amount')
                    ->label(__('Expenses'))
                    ->state(fn (CashflowEntry $record): float => $record->entry_type === 'expense' ? (float) $record->amount : 0.0)
                    ->numeric(decimalPlaces: 2)
                    ->summarize(Sum::make()->label(__('Expense total'))),
                Tables\Columns\TextColumn::make('payment_method')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => __('Cash'),
                        'ecocash' => __('EcoCash'),
                        'zipit' => __('ZIPIT'),
                        'bank_transfer' => __('Bank transfer'),
                        'other' => __('Other'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('entry_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label(__('Client'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice.invoice_number')
                    ->label(__('Invoice'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('recordedBy.name')
                    ->label(__('Recorded by'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('entry_date', 'desc')
            ->filters([
                SelectFilter::make('entry_type')
                    ->label(__('Entry type'))
                    ->options([
                        'income' => __('Income'),
                        'expense' => __('Expense'),
                    ]),
                SelectFilter::make('payment_method')
                    ->label(__('Payment method'))
                    ->options([
                        'cash' => __('Cash'),
                        'ecocash' => __('EcoCash'),
                        'zipit' => __('ZIPIT'),
                        'bank_transfer' => __('Bank transfer'),
                        'other' => __('Other'),
                    ]),
                SelectFilter::make('period_preset')
                    ->label(__('Quick period'))
                    ->options([
                        'today' => __('Today'),
                        'this_month' => __('This month'),
                        'this_year' => __('This year'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;
                        if (blank($value)) {
                            return $query;
                        }

                        return match ($value) {
                            'today' => $query->whereDate('entry_date', today()),
                            'this_month' => $query->whereBetween('entry_date', [
                                now()->startOfMonth()->toDateString(),
                                now()->endOfMonth()->toDateString(),
                            ]),
                            'this_year' => $query->whereBetween('entry_date', [
                                now()->startOfYear()->toDateString(),
                                now()->endOfYear()->toDateString(),
                            ]),
                            default => $query,
                        };
                    }),
                Filter::make('entry_date_range')
                    ->label(__('Date range'))
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label(__('From')),
                        Forms\Components\DatePicker::make('until')
                            ->label(__('Until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $q, string $date): Builder => $q->whereDate('entry_date', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $q, string $date): Builder => $q->whereDate('entry_date', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if (filled($data['from'] ?? null)) {
                            $indicators[] = Indicator::make(__('From').' '.$data['from']);
                        }
                        if (filled($data['until'] ?? null)) {
                            $indicators[] = Indicator::make(__('Until').' '.$data['until']);
                        }

                        return $indicators;
                    }),
            ])
            ->groups([
                Group::make('entry_date')
                    ->label(__('Day'))
                    ->date()
                    ->collapsible(),
            ])
            ->groupingSettingsInDropdownOnDesktop()
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            CashflowTotalsOverview::class,
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCashflowEntries::route('/'),
            'create' => Pages\CreateCashflowEntry::route('/create'),
            'edit' => Pages\EditCashflowEntry::route('/{record}/edit'),
        ];
    }
}
