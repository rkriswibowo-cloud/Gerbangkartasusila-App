<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin';
    protected $description = 'Create a new administrator using an interactive password prompt';

    public function handle(): int
    {
        $data = ['name' => $this->ask('Name'), 'email' => $this->ask('Email'), 'password' => $this->secret('Password (at least 12 characters)')];
        $validator = Validator::make($data, ['name' => 'required|string|max:255', 'email' => 'required|email|max:255|unique:users,email', 'password' => 'required|string|min:12']);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return self::FAILURE;
        }
        \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $user = User::create(array_merge($data, ['password' => Hash::make($data['password']), 'status' => 'active']));
            $user->assignRole(Role::findOrCreate('super_admin', 'web'));
        });
        $this->info('Administrator created. Sign in at /admin.');
        return self::SUCCESS;
    }
}
