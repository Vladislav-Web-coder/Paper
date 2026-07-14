<?php

namespace App\Services;

use App\Contracts\SettingsTabHandler;
use App\Enums\SettingsTab;
use App\Models\User;
use App\Services\Settings\InterfaceTabHandler;
use App\Services\Settings\NotificationsTabHandler;
use App\Services\Settings\PrivacyTabHandler;
use http\Exception\InvalidArgumentException;

class SettingsService
{
    protected function getHandler(string $tab): SettingsTabHandler
    {
        return match ($tab) {
            SettingsTab::INTERFACE->value => new InterfaceTabHandler(),
            SettingsTab::NOTIFICATIONS->value => new NotificationsTabHandler(),
            SettingsTab::PRIVACY->value => new PrivacyTabHandler(),
            default => throw new InvalidArgumentException("Invalid tab: $tab"),
        };
    }

    public function getTabData(User $user, string $tab): array
    {
        return $this->getHandler($tab)->handleShow($user);
    }
    public function updateTabData(User $user, string $tab, array $data): void
    {
        $this->getHandler($tab)->handleUpdate($user, $data);
    }

}
