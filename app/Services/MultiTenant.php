<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 3/10/17
 * Time: 6:56 PM
 */

namespace App\Services;


use App\Client;
use App\Exceptions\TableBuilder\CreateTableBuilderException;
use App\Jobs\ImportData;
use App\Jobs\ImportDb;
use Carbon\Carbon;
use CityNexus\CityNexus\Table;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MultiTenant
{

    use DispatchesJobs;

    public function __construct()
    {
        $this->dbImport = new DbImport();
    }

    public function createClient($name, $subdomain)
    {
        $domain = $subdomain . '.'  . env('ROOT_DOMAIN');

        $client = Client::firstornew(['domain' => $domain]);


        if($client->exists)
        {
            App::abort(500, 'Domain already exists');
        }
        else
        {
            $client->name = $name;

            /******
             *
             * Set Version number
             *
             * *******/

            $client->version_id = 1;


            $client->schema = strtolower(trim(str_replace('-', '_', $subdomain))) .'_' . random_int(10000,99999);
            $client->save();
            $this->createSchema($client->schema);
            $this->migrate($client);
        }

        return $client;
    }

    public function createSchema($schema)
    {

        try
        {
            DB::statement('CREATE SCHEMA ' . $schema);

        }
        catch(\Exception $e) {
            throw new CreateTableBuilderException('create_schema_failed');
        }


        try
        {
            $oldSchema = config('database.connections.tenant.schema');

            config(['database.connections.tenant.schema' => $schema]);

            Schema::connection('tenant')->create($schema . '.migrations', function (Blueprint $table){
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });

            config(['database.connections.tenant.schema' => $oldSchema]);
        }
        catch(\Exception $e) {
            throw new CreateTableBuilderException('migration_table_failed');
        }

        return true;
    }

    /**
     * @param Client $client
     * @return bool|\Exception
     */
    public function migrate(Client $client)
    {
        $client->logInAsClient();

        DB::statement('SET search_path TO ' . $client->schema . ',public');

        Artisan::call('migrate', ['--force' => 'true','--database' => 'tenant']);

        $client->migrated_at = Carbon::now();
        $client->save();

        return 'migrated';

    }

    public function resetDb($id)
    {
        try{
            $client = Client::find($id);
            DB::statement('DROP SCHEMA ' . $client->schema . ' CASCADE');
            $this->createSchema($client->schema);
            $this->migrate($client);
        }
        catch (\Exception $e)
        {
            return $e;
        }

        return true;

    }

    public function importDb($target_id, $source)
    {

        $importDb = [
            'driver'   => 'pgsql',
            'host'     => $source['host'],
            'database' => $source['name'],
            'username' => $source['user'],
            'password' => $source['password'],
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => $source['schema'],
        ];

        config([
            'database.connections.import' => $importDb,
        ]);

        $client = Client::find($target_id);

        $import_tables = [
            'api_keys',
            'citynexus_api_requests',
            'citynexus_api_secrets',
            'citynexus_exports',
            'citynexus_file_versions',
            'citynexus_files',
            'citynexus_images',
            'citynexus_locations',
            'citynexus_notes',
            'citynexus_properties',
            'citynexus_raw_addresses',
            'citynexus_report_view',
            'citynexus_reports',
            'citynexus_scores',
            'citynexus_searches',
            'citynexus_settings',
            'citynexus_tags',
            'citynexus_taskables',
            'citynexus_tasks',
            'citynexus_uploaders',
            'citynexus_uploads',
            'citynexus_user_groups',
            'citynexus_widgets',
            'property_tag',
            'tabler_tables',
            'users'
        ];

        foreach($import_tables as $table)
        {
            if(DB::table($table)->count() > 0)
            {
                $this->dispatch(new ImportDb($table, $source, $client->schema));
            }
        }

        $tabler = new Table();

        $tabler->setConnection('import');
        $tables = $tabler->whereNotNull('schema');

        foreach ($tables as $table) {
            $this->dispatch(new ImportData($table, $source, $client->schema));
        }

    }

}