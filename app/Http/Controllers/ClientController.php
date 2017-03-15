<?php

namespace App\Http\Controllers;

use App\Client;
use App\Jobs\ImportData;
use App\Jobs\ImportDb;
use App\Services\MultiTenant;
use BackupManager\Manager;
use CityNexus\CityNexus\Table;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MultiTenant $multiTenant)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'domain' => 'required|max:50'
        ]);

        $client = $multiTenant->createClient($request->get('name'), $request->get('domain'));

        return redirect(route('admin.client.show', [$client->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function resetDb($id, MultiTenant $multiTenant)
    {
        if($multiTenant->resetDb($id)) return response(200);
        else return response(500);
    }

    public function migrateDb($id, MultiTenant $multiTenant)
    {
        return $multiTenant->migrate(Client::find($id));
    }

    public function importDb(Request $request)
    {
        $client = Client::find($request->get('import_id'));

        $importDb = [
            'driver'   => 'pgsql',
            'host'     => 'localhost',
            'database' => 'homestead',
            'username' => 'homestead',
            'password' => 'secret',
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ];

        config([
            'database.connections.import' => $importDb,
        ]);

        Artisan::call("db:backup", ['database' => 'input', 'destination' => 'aws', 'destinationPath' => $client->schema, 'compression' => 'gzip']);

        config(['database.connections.tenant.schema' => $client->schema]);

        Artisan::call("db:restore", ['database' => 'tenant', 'destination' => 'aws', 'destinationPath' => $client->schema, 'compression' => 'gzip']);

        return 'hello';

    }
    public function importDbOld(Request $request)
    {
//        $importDb = [
//            'driver'   => 'pgsql',
//            'host'     => $request->get('host'),
//            'database' => $request->get('name'),
//            'username' => $request->get('user'),
//            'password' => $request->get('password'),
//            'charset'  => 'utf8',
//            'prefix'   => '',
//            'schema'   => $request->get('schema'),
//        ];

        $importDb = [
            'driver'   => 'pgsql',
            'host'     => 'localhost',
            'database' => 'homestead',
            'username' => 'homestead',
            'password' => 'secret',
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ];

        config([
            'database.connections.import' => $importDb,
        ]);

        $client = Client::find($request->get('import_id'));

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
            'citynexus_report_views',
            'citynexus_reports',
            'citynexus_scores',
            'citynexus_searches',
            'citynexus_tags',
            'citynexus_tasks',
            'citynexus_uploaders',
            'citynexus_uploads',
            'citynexus_user_groups',
            'tabler_tables',
            'users'
        ];

        foreach($import_tables as $table)
        {
            $this->dispatch(new ImportDb($table, $importDb, $client->schema));
        }

        $tabler = new Table();
        $tabler->setConnection('import');
        $tables = $tabler->whereNotNull('scheme')->get();
        foreach ($tables as $table) {
            $this->dispatch(new ImportData($table, $importDb, $client->schema));
        }
    }

    public function config($id)
    {
        $client = Client::find($id);
        $config = $client->settings;

        return view('admin.clients.config', compact('config', 'client'));
    }

    public function postConfig($id, Request $request)
    {
        $client = Client::find($id);
        $client->settings = $request->get('config');
        $client->save();

        return redirect('/');
    }
}
