<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\DemoReadOnlyResource;
use App\Filament\Resources\CashflowEntryResource\Pages;
use App\Models\CashflowEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                    ->numeric()
                    ->sortable(),
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
