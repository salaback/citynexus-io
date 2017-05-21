<?php

namespace App\Console\Commands;

use App\Client;
use App\Services\IndexSearch;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class BuildSearchIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citynexus:searchindex {client_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(IndexSearch $indexSearch)
    {
        if($this->argument('client_id') != null)
        {
            $indexSearch->run($this->argument('client_id'));
        }
        foreach(Client::all() as $client)
        {
            $indexSearch->run($client->id);
        }
    }
}
