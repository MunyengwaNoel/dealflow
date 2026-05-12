<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DealFlowQuickActions extends Widget
{
    protected static string $view = 'filament.widgets.dealflow-quick-actions';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 0;
}
