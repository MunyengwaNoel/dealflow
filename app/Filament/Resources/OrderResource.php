<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Pages\OrderWizardPage;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\OrderItemsRelationManager;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 18;

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client.name')->label('Client')->searchable(),
                Tables\Columns\TextColumn::make('services_summary')->label('Services')->limit(40),
                Tables\Columns\TextColumn::make('total_amount')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('profit_margin')->suffix('%')->label('Margin'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof \BackedEnum ? $state->value : (string) $state)
                    ->color(fn ($state): string => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'draft' => 'gray',
                        'quoted' => 'warning',
                        'completed' => 'success',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'quoted' => 'Quoted',
                    'accepted' => 'Accepted',
                    'in_progress' => 'In progress',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('continue')
                    ->label('Continue wizard')
                    ->url(fn (Order $record): string => OrderWizardPage::getUrl().'?order='.$record->id)
                    ->visible(fn (Order $record): bool => $record->status === OrderStatus::Draft),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Order')->schema([
                    TextEntry::make('order_number'),
                    TextEntry::make('client.name')->label('Client'),
                    TextEntry::make('status')
                        ->formatStateUsing(fn ($state) => $state instanceof \BackedEnum ? $state->value : (string) $state),
                    TextEntry::make('services_summary')->label('Services'),
                    TextEntry::make('total_amount')->money('USD'),
                    TextEntry::make('profit_amount')->money('USD')->label('Profit'),
                    TextEntry::make('profit_margin')->suffix('%')->label('Margin %'),
                    TextEntry::make('quote.quote_number')->label('Quote'),
                ])->columns(2),
                Section::make('Wizard state')->schema([
                    TextEntry::make('wizard_state')
                        ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
