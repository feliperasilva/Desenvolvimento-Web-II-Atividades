<?php

namespace App\Policies;

use App\Models\Publisher;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PublisherPolicy
{
    public function viewAny(User $user): bool
{
    return true;
}

public function view(User $user, Publisher $publisher): bool
{
    return true;
}

public function create(User $user): bool
{
    return in_array($user->role, ['admin', 'bibliotecario']);
}

public function update(User $user, Publisher $publisher): bool
{
    return in_array($user->role, ['admin', 'bibliotecario']);
}

public function delete(User $user, Publisher $publisher): bool
{
    return $user->role === 'admin';
}
}
