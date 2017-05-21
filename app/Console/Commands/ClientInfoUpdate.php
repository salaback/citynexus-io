<?php

namespace App\Console\Commands;

use App\Client;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClientInfoUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citynexus:client-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload using dropbox API';

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
    public function handle()
    {
        $clients = Client::all();

        foreach($clients as $client)
        {
            $info = [
                'user_count' => 0,

            ];

            $users = User::all();

            foreach($users as $user)
            {
                if(isset($user->memberships[$client->domain])) $info['user_count'] = $info['user_count'] + 1;
            }

            $info['dataset_count'] = DB::table($client->schema . '.cn_datasets')->whereNotNull('deleted_at')->count();

            $client->info = $this->updateInfo($client, $info);

        }



        print "All client info updated \n";
    }

    private function updateInfo($client, $new)
    {
        $old = $client->info;
        foreach($new as $k => $i)
        {
            $old[$k] = $i;
        }

        $client->info = $old;
        $client->save();
    }

}
