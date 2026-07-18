<?php

namespace App\Services\Settings;

use App\Contracts\SettingsTabHandler;
use App\Models\User;

class NotificationsTabHandler implements SettingsTabHandler
{

    public function handleShow(User $user): array
    {
        return [];
    }

    public function handleUpdate(User $user, array $data): void
    {
        unset($data['current_tab']);
        $user->saveSettings($data);
    }
}
