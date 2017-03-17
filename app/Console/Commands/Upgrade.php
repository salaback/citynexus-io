<?php

namespace App\Console\Commands;

use App\Client;
use CityNexus\CityNexus\Dropbox;
use CityNexus\CityNexus\Uploader;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;

class Upgrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upgrade {client_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade client version';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
