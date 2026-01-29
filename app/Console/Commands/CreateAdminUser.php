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
        $this->info('=== Crear Usuario Administrador ===');
        $this->newLine();

        // Verificar si ya existe un usuario administrador
        $adminExists = User::where('role', 'admin')->exists();

        if ($adminExists) {
            $this->warn('Ya existe un usuario administrador en el sistema.');

            if (! $this->confirm('¿Deseas crear otro usuario administrador de todas formas?', false)) {
                $this->info('Operación cancelada.');

                return Command::SUCCESS;
            }
        }

        // Solicitar datos del usuario
        $name = $this->ask('Nombre del administrador');
        $email = $this->ask('Email del administrador');
        $password = $this->secret('Contraseña (mínimo 8 caracteres)');
        $passwordConfirm = $this->secret('Confirmar contraseña');

        // Validar que las contraseñas coincidan
        if ($password !== $passwordConfirm) {
            $this->error('Las contraseñas no coinciden.');

            return Command::FAILURE;
        }

        // Validar datos
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
            $this->error('Error en la validación:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('- '.$error);
            }

            return Command::FAILURE;
        }

        try {
            // Crear el usuario administrador
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);

            $this->newLine();
            $this->info('✓ Usuario administrador creado exitosamente!');
            $this->newLine();
            $this->table(
                ['ID', 'UUID', 'Nombre', 'Email', 'Rol'],
                [[$user->id, $user->uuid, $user->name, $user->email, $user->role]]
            );

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error al crear el usuario: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
