<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Event;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {   
        $user = auth()->user();
        if  ($user->isNotAdmin() )
        {
            $data['user_id'] = auth()->id();
            $data['Status'] = Event::PENDING;
        }
     

        return $data;
    }
}
