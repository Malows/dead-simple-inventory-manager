<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the first administrator user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Create Admin User ===');
        $this->newLine();

        // Check if an admin user already exists
        $adminExists = User::where('role', 'admin')->exists();

        if ($adminExists) {
            $this->warn('An admin user already exists in the system.');

            if (! $this->confirm('Do you want to create another admin user anyway?', false)) {
                $this->info('Operation cancelled.');

                return Command::SUCCESS;
            }
        }

        // Request user data
        $name = $this->ask('Administrator name');
        $email = $this->ask('Administrator email');
        $password = $this->secret('Password (minimum 8 characters)');
        $passwordConfirm = $this->secret('Confirm password');

        // Validate that passwords match
        if ($password !== $passwordConfirm) {
            $this->error('Passwords do not match.');

            return Command::FAILURE;
        }

        // Validate data
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('Validation error:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('- '.$error);
            }

            return Command::FAILURE;
        }

        try {
            // Create the admin user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);

            $this->newLine();
            $this->info('✓ Admin user created successfully!');
            $this->newLine();
            $this->table(
                ['ID', 'UUID', 'Name', 'Email', 'Role'],
                [[$user->id, $user->uuid, $user->name, $user->email, $user->role]]
            );

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error creating user: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
