<?php

namespace App\Filament\Pages;

use App\Enums\DealStage;
use App\Models\Deal;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;

class DealKanbanBoard extends \Mokhosh\FilamentKanban\Pages\KanbanBoard
{
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    protected static string $model = Deal::class;

    protected static string $statusEnum = DealStage::class;

    protected static string $recordStatusAttribute = 'stage';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 31;

    protected static ?string $title = 'Deals pipeline';

    protected static ?string $navigationLabel = 'Pipeline';

    protected function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['client', 'assignedTo']);
    }
}
