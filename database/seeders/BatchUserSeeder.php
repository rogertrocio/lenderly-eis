<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BatchUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        $maxUsersCount = 100000;
        $chunkSize = 5000;

        $this->command->info('Preparing to generate users...');

        $this->command->newLine();

        $usersCount = max((int) $this->command->ask('How many users would you like to generate?', $maxUsersCount), 1);

        $maxChunkSize = min((int) $this->command->ask('Maximum chunk size in generating batch users?', $chunkSize), $chunkSize);

        for ($i = 0; $i < $usersCount; $i++) {
            $user = User::factory()->make();
            $data[] = [
                'name' => $user->name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'phone' => $user->phone,
                'job' => $user->job,
                'avatar' => $user->avatar,
                'remember_token' => $user->remember_token,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($data) >= $maxChunkSize) {
                User::insert($data);
                $data = [];
            }
        }

        if (!empty($data)) {
            User::insert($data);
        }

        $this->command->info("A total of $usersCount users have been successfully generated.");
    }
}
