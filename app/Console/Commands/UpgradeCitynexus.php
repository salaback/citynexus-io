<?php

namespace App\Console\Commands;

use App\Client;
use Illuminate\Console\Command;

class UpgradeCitynexus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citynexus:fromv1 {client_id} {host} {database} {user_name} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade CityNexus Instance';

    /**
     * Create a new command instance.
     *
     * @param \App\Services\Upgrade $upgrade
     */

    protected $upgrade;

    public function __construct(\App\Services\CityNexusUpgrade $upgrade)
    {
        parent::__construct();

        $this->upgrade = $upgrade;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Client::find($this->argument('client_id'))->logInAsClient;

        $credentials = [
            'driver'   => 'pgsql',
            'host'     => $this->argument('host'),
            'database' => $this->argument('database'),
            'username' => $this->argument('user_name'),
            'password' => $this->argument('password'),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ];

        config(['database.connections.target' => $credentials]);


        $this->upgrade->run();

    }
}
