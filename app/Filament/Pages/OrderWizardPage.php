<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class OrderWizardPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static string $view = 'filament.pages.order-wizard-page';

    protected static ?string $navigationLabel = 'New order';

    protected static ?string $title = 'Guided order wizard';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 12;

    protected static ?string $slug = 'order-wizard';

    protected ?string $maxContentWidth = 'full';
}
