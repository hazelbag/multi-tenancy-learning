<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:tenant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Tenant';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $id = $this->ask("Tenant ID?");

        $domain = "{$id}.localhost";

        if ($this->confirm("Create tenant $id with domain $domain?"))
        {
            $tenant = Tenant::create([
                'id' => $id
            ]);

            $tenant->domains()->create([
                'domain' => $domain
            ]);

            $this->info("Successfully created tenant with ID $id and domain $domain");
        }

        return 0;
    }
}
