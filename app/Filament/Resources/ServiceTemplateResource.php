<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\DemoReadOnlyResource;
use App\Filament\Resources\ServiceTemplateResource\Pages;
use App\Filament\Resources\ServiceTemplateResource\RelationManagers;
use App\Models\ServiceTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceTemplateResource extends Resource
{
    use DemoReadOnlyResource;

    protected static ?string $model = ServiceTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('template_code')
                    ->label('Template ID')
                    ->maxLength(32)
                    ->placeholder('e.g. SRV-WEB-001'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('category')
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'archived' => 'Archived',
                    ])
                    ->default('active'),
                Forms\Components\TextInput::make('version_label')
                    ->maxLength(16)
                    ->default('1.0'),
                Forms\Components\TextInput::make('timeline_days')
                    ->numeric()
                    ->minValue(1)
                    ->label('Default timeline (days)'),
                Forms\Components\TextInput::make('cost_price')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('sell_price')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Textarea::make('pricing_structure')
                    ->label('Pricing structure (JSON)')
                    ->helperText('Tiers, add-ons, bundles — JSON object.')
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : (string) $state)
                    ->dehydrateStateUsing(function ($state) {
                        if ($state === null || $state === '') {
                            return null;
                        }
                        if (is_array($state)) {
                            return $state;
                        }

                        return json_decode((string) $state, true);
                    })
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('demo_links')
                    ->label('Demo links (JSON array of URLs)')
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : (string) $state)
                    ->dehydrateStateUsing(function ($state) {
                        if ($state === null || $state === '') {
                            return null;
                        }
                        if (is_array($state)) {
                            return $state;
                        }

                        return json_decode((string) $state, true);
                    })
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('required_documents')
                    ->label('Required documents (JSON)')
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : (string) $state)
                    ->dehydrateStateUsing(function ($state) {
                        if ($state === null || $state === '') {
                            return null;
                        }
                        if (is_array($state)) {
                            return $state;
                        }

                        return json_decode((string) $state, true);
                    })
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('deliverables')
                    ->label('Deliverables (JSON array)')
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : (string) $state)
                    ->dehydrateStateUsing(function ($state) {
                        if ($state === null || $state === '') {
                            return null;
                        }
                        if (is_array($state)) {
                            return $state;
                        }

                        return json_decode((string) $state, true);
                    })
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('automation_rules')
                    ->label('Automation rules (JSON)')
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : (string) $state)
                    ->dehydrateStateUsing(function ($state) {
                        if ($state === null || $state === '') {
                            return null;
                        }
                        if (is_array($state)) {
                            return $state;
                        }

                        return json_decode((string) $state, true);
                    })
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('template_code')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('cost_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sell_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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
            'index' => Pages\ListServiceTemplates::route('/'),
            'create' => Pages\CreateServiceTemplate::route('/create'),
            'edit' => Pages\EditServiceTemplate::route('/{record}/edit'),
        ];
    }
}
