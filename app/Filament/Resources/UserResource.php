<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\DemoReadOnlyResource;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    use DemoReadOnlyResource;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 50;

    /**
     * Only tenant admins may browse or manage the registered user list.
     */
    protected static function authUserIsTenantAdmin(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasAdminRole();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::authUserIsTenantAdmin();
    }

    public static function canViewAny(): bool
    {
        return static::authUserIsTenantAdmin();
    }

    public static function canCreate(): bool
    {
        return static::authUserIsTenantAdmin() && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return static::authUserIsTenantAdmin() && parent::canEdit($record);
    }

    public static function canDelete(Model $record): bool
    {
        return static::authUserIsTenantAdmin() && parent::canDelete($record);
    }

    public static function canDeleteAny(): bool
    {
        return static::authUserIsTenantAdmin() && parent::canDeleteAny();
    }

    public static function canForceDelete(Model $record): bool
    {
        return static::authUserIsTenantAdmin() && parent::canForceDelete($record);
    }

    public static function canForceDeleteAny(): bool
    {
        return static::authUserIsTenantAdmin() && parent::canForceDeleteAny();
    }

    public static function canRestore(Model $record): bool
    {
        return static::authUserIsTenantAdmin() && parent::canRestore($record);
    }

    public static function canRestoreAny(): bool
    {
        return static::authUserIsTenantAdmin() && parent::canRestoreAny();
    }

    public static function canView(Model $record): bool
    {
        return static::authUserIsTenantAdmin() && parent::canView($record);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(50),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn (?string $state): bool => filled($state)),
                Forms\Components\Select::make('role')
                    ->options([
                        'owner' => 'Owner',
                        'admin' => 'Admin',
                        'staff' => 'Staff',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('avatar')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge(),
                Tables\Columns\TextColumn::make('avatar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        $tenantId = auth()->user()?->tenant_id;
        if ($tenantId !== null) {
            $query->where($query->qualifyColumn('tenant_id'), $tenantId);
        }

        return $query;
    }
}
