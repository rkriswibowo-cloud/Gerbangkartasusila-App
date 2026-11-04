<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckReadiness extends Command
{
    protected $signature = 'app:check-readiness';
    protected $description = 'Read-only database and runtime checks without printing credentials or personal data';

    public function handle(): int
    {
        $failed = false;
        try {
            DB::connection()->getPdo();
            $this->info('Database connection: OK ('.DB::connection()->getDriverName().')');
            foreach (['users', 'destinations', 'destination_categories', 'events', 'hotels', 'culinary_places', 'articles', 'tickets', 'roles', 'personal_access_tokens'] as $table) {
                if (!Schema::hasTable($table)) {
                    $this->error($table.': MISSING');
                    $failed = true;
                    continue;
                }
                $this->line($table.': '.DB::table($table)->count().' rows');
            }
            $this->line('Tickets without owner: '.DB::table('tickets')->whereNull('user_id')->count());
            $adminCount = \App\Models\User::whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->count();
            $this->line('Administrator accounts: '.$adminCount);
            if ($adminCount === 0) $this->warn('Create an administrator with php artisan app:create-admin before using the admin panel.');
            $demoPasswords = \App\Models\User::cursor()->filter(function ($user) {
                return password_verify('password', $user->password) || password_verify('password123', $user->password);
            })->count();
            $this->line('Accounts using known demo passwords: '.$demoPasswords);
            if ($demoPasswords > 0) $this->warn('Change demo passwords before deploying an existing database.');
            if (app()->environment('production') && ($adminCount === 0 || $demoPasswords > 0 || config('app.debug'))) {
                $failed = true;
            }
        } catch (\Throwable $e) {
            $this->error('Database checks failed ('.get_class($e).'). Check server configuration and migrations.');
            $failed = true;
        }
        foreach (['storage', 'bootstrap/cache'] as $path) {
            $writable = is_writable(base_path($path));
            $this->line($path.': '.($writable ? 'writable' : 'NOT WRITABLE'));
            $failed = $failed || !$writable;
        }
        $this->line('Environment: '.app()->environment());
        $this->line('Debug: '.(config('app.debug') ? 'ON' : 'OFF'));
        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
