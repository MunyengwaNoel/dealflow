<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['password'])) {
            throw ValidationException::withMessages([
                'data.password' => __('The password field is required.'),
            ]);
        }

        $data['tenant_id'] = auth()->user()?->tenant_id;

        return $data;
    }
}
