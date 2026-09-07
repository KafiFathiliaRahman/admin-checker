<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AdminCreateCommand extends Command
{
    protected $signature = 'admin:create
                            {name : Nama lengkap admin}
                            {email : Email admin}
                            {password? : Password (default: password)}
                            {--force : Ganti password jika email sudah terdaftar}';

    protected $description = 'Buat akun admin untuk dashboard Admin Checker';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password') ?: 'password';
        $force = $this->option('force');

        if (! $force && User::where('email', $email)->exists()) {
            $this->error("Email {$email} sudah terdaftar. Pakai --force untuk menimpa.");
            return 1;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $this->info("Akun admin dibuat/diperbarui: {$email} ({$name})");

        return 0;
    }
}
