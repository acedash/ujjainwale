<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Notifications\TeamInvitation;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data, string $model): Model {
                    // Set a dummy password initially
                    $data['password'] = Hash::make(Str::random(32));

                    $user = $model::create($data);

                    // The roles are automatically synced by Filament because of the select relationship

                    // Send invitation notification
                    $user->notify(new TeamInvitation);

                    return $user;
                }),
        ];
    }
}
