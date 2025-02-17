<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ChangeUserRole extends Command
{
    protected $signature = 'user:change-role {userId} {role}';
    protected $description = 'Change the role of a user';

    public function handle()
    {
        $userId = $this->argument('userId');
        $role = $this->argument('role');

        $user = User::find($userId);

        if (!$user) {
            $this->error('User not found.');
            return;
        }

        if (!in_array($role, ['admin', 'client'])) {
            $this->error('Invalid role. Allowed roles: admin, client.');
            return;
        }

        $user->role = $role;
        $user->save();

        $this->info("User role updated to $role.");
    }
}
