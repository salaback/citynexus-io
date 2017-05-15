<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 3/28/17
 * Time: 3:21 PM
 */

namespace App\DataStore;


class processSql extends DataProcessor
{
    public function sql(Uploader $uploader)
    {
        // load source db
        $sourceDb = [
            'driver'   => $uploader->settings['driver'],
            'host'     => $uploader->settings['host'],
            'database' => $uploader->settings['database'],
            'username' => $uploader->settings['username'],
            'password' => $uploader->settings['password'],
            'charset'  => $uploader->settings['charset'],
            'prefix'   => $uploader->settings['prefix'],
            'schema'   => $uploader->settings['schema'],
        ];
        config(['database.connections.source' => $sourceDb]);

        $data = DB::connection('source')->table($uploader->settings['table_name'])->get();


    }
}