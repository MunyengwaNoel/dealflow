<?php

namespace App\Filament\Resources;

use App\Enums\DealPriority;
use App\Enums\DealStage;
use App\Filament\Concerns\DemoReadOnlyResource;
use App\Filament\Resources\DealResource\Pages;
use App\Models\Deal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DealResource extends Resource
{
    use DemoReadOnlyResource;

    protected static ?string $model = Deal::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Select::make('stage')
                    ->options(collect(DealStage::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getTitle()]))
                    ->required(),
                Forms\Components\Select::make('priority')
                    ->options(collect(DealPriority::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()]))
                    ->required(),
                Forms\Components\Select::make('service_template_id')
                    ->relationship('serviceTemplate', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Service template'),
                Forms\Components\TextInput::make('value')
                    ->numeric()
                    ->default(0.00)
                    ->prefix('$'),
                Forms\Components\TextInput::make('probability_percent')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(25),
                Forms\Components\DatePicker::make('expected_close_date'),
                Forms\Components\DatePicker::make('actual_close_date'),
                Forms\Components\TextInput::make('source')
                    ->maxLength(255),
                Forms\Components\TextInput::make('competitor_name')
                    ->maxLength(255),
                Forms\Components\Textarea::make('lost_reason')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\Select::make('assigned_to')
                    ->relationship('assignedTo', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('deal_number')
                    ->label('Deal #')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stage')
                    ->badge(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge(),
                Tables\Columns\TextColumn::make('priority_score')
                    ->label('Score')
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_close_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Agent')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListDeals::route('/'),
            'create' => Pages\CreateDeal::route('/create'),
            'edit' => Pages\EditDeal::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
