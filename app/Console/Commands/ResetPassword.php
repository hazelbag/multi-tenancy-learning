<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:reset-password {email} {tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the password for a tenant user';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $email = $this->argument('email');
        $tenant = $this->argument('tenant');

        tenancy()->initialize($tenant);
        $user = User::where('email', $email)->first();
        $this->info("Reset password for User: {$user->name} in Tenant: $tenant");

        if (!$user) {
            $this->error('User not found');
            $this->error("User not found for $email in tenant $tenant");
        }

        $user->password = bcrypt('password');
        $this->info("Password reset for $email in tenant $tenant, password has been set to 'password'");
        $user->save();
        $this->info('User password reset');
    }
}
