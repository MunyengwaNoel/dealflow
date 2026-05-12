<?php

namespace App\Filament\Concerns;

use App\Support\DemoUser;
use Illuminate\Database\Eloquent\Model;

trait DemoReadOnlyResource
{
    public static function canCreate(): bool
    {
        return ! DemoUser::isDemo() && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return ! DemoUser::isDemo() && parent::canEdit($record);
    }

    public static function canDelete(Model $record): bool
    {
        return ! DemoUser::isDemo() && parent::canDelete($record);
    }

    public static function canForceDelete(Model $record): bool
    {
        return ! DemoUser::isDemo() && parent::canForceDelete($record);
    }

    public static function canDeleteAny(): bool
    {
        return ! DemoUser::isDemo() && parent::canDeleteAny();
    }

    public static function canForceDeleteAny(): bool
    {
        return ! DemoUser::isDemo() && parent::canForceDeleteAny();
    }
}
