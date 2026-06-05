<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Process;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        $username = getenv('USER');
        $password = 'password';

        User::factory()->withPersonalTeam()->create([
            'name' => getenv('USER'),
            'email' => getenv('USER') . '@email.com',
            'password' => $password,
            'current_team_id' => 1,
        ]);

        Process::run("echo '{$username}:{$password}' | sudo chpasswd");
    }
}
