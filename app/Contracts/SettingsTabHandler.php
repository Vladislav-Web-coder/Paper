<?php

namespace App\Contracts;

use App\Models\User;

interface SettingsTabHandler
{
    public function handleShow(User $user): array;

    public function handleUpdate(User $user, array $data): void;
}
