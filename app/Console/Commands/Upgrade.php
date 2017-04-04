<?php

namespace App\Console\Commands;

use App\Client;
use Illuminate\Console\Command;

class Upgrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citynexus:upgrade {client_id}';

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
    public function __construct(\App\Services\Upgrade $upgrade)
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
        $client = Client::find($this->argument('client_id'));

        try
        {
            $this->upgrade->client($client);

            print "Client updated \n";
        }
        catch (\Exception $e)
        {
            print $e;
        }
    }
}
