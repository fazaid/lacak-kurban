<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Administrator NPC',
                'email'    => 'admin@npc.id',
                'role'     => 'admin',
                'password' => 'admin123',
            ],
            [
                'name'     => 'Staff NPC',
                'email'    => 'staff@npc.id',
                'role'     => 'staff',
                'password' => 'staff123',
            ],
            [
                'name'     => 'Viewer NPC',
                'email'    => 'viewer@npc.id',
                'role'     => 'viewer',
                'password' => 'viewer123',
            ],
        ];

        $this->command->info('Creating admin users...');
        $this->command->newLine();

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'role'              => $data['role'],
                    'password'          => Hash::make($data['password']),
                    'email_verified_at' => now(),
                ]
            );

            $action = $user->wasRecentlyCreated ? '<fg=green>CREATED</>' : '<fg=yellow>UPDATED</>';
            $this->command->line("  [{$action}] {$user->name}");
            $this->command->line("         Email    : {$user->email}");
            $this->command->line("         Role     : {$user->role}");
            $this->command->line("         Password : {$data['password']}");
            $this->command->newLine();
        }

        $this->command->warn('⚠️  IMPORTANT: Change passwords in production!');
    }
}
