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
    protected $signature = 'citynexus:fromv1';

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
        $clientId = $this->ask('What is the client ID?');
        $host = $this->ask('Host?');
        $database = $this->ask('Database Name?');
        $user = $this->ask('User Name?');
        $password = $this->ask('Password?');

        Client::find($clientId)->logInAsClient;

        $credentials = [
            'driver'   => 'pgsql',
            'host'     => $host,
            'database' => $database,
            'username' => $user,
            'password' => $password,
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ];

        config(['database.connections.target' => $credentials]);


        $this->upgrade->run();

    }
}
