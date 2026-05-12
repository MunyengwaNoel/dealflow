<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Pages\OrderWizardPage;
use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('new_order')
                ->label('New order')
                ->icon('heroicon-o-sparkles')
                ->url(fn (): string => OrderWizardPage::getUrl().'?client='.$this->getRecord()->getKey()),
            Actions\EditAction::make(),
        ];
    }
}
