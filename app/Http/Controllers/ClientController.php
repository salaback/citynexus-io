<?php

namespace App\Http\Controllers;

use App\Client;
use App\Jobs\ImportData;
use App\Jobs\ImportDb;
use App\Services\MultiTenant;
use App\User;
use BackupManager\Manager;
use CityNexus\CityNexus\TableBuilder;
use Illuminate\Database\Schema\Blueprint;
use CityNexus\CityNexus\Table;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        return redirect(action('AdminController@index'));
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

    public function importDb(Request $request, MultiTenant $multiTenant)
    {
        $importDb = [
            'driver'   => 'pgsql',
            'host'     => $request->get('host'),
            'database' => $request->get('database'),
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => $request->get('schema'),
        ];

        $client = Client::find($request->get('client_id'));

        config([
            'database.connections.import' => $importDb,
            'database.connections.tenant.schema' => $client->schema
        ]);

        $existing = DB::connection('tenant')->table('information_schema.tables')->where('table_schema', '=', $client->schema)->get(['table_name']);

        foreach($existing as $item)
        {
            $current[$item->table_name] = DB::connection('tenant')->table($item->table_name)->count();
        }

        $new = DB::connection('import')->table('information_schema.tables')->where('table_schema', '=', $importDb['schema'])->get();
        $data = DB::connection('import')->table('tabler_tables')->whereNull('deleted_at')->whereNotNull('table_name')->pluck('table_name');

        $datasets = [];

        foreach($data as $i)
        {
            $datasets[$i] = true;
        }

        foreach ($new as $item)
        {
            $results[$item->table_name] = DB::connection('import')->table($item->table_name)->count();
        }

//        foreach($import_tables as $table)
//        {
//            dd(DB::table("citynexus_notes")->count());
//            if(DB::table($table)->count() > 0)
//            {
//            }
//        }
//
//        $tabler = new Table();
//        $tabler->setConnection('import');
//        $tables = $tabler->whereNotNull('scheme')->get();
//        foreach ($tables as $table) {
//            $this->dispatch(new ImportData($table, $importDb, $client->schema));
//        }

        return view('admin.clients.import', compact('importDb', 'results', 'client', 'current', 'datasets'));
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

        return redirect(action('Admin\AdminController@index'));
    }

    public function importTable(Request $request, TableBuilder $tableBuilder)
    {
        $importDb = [
            'driver' => 'pgsql',
            'host' => $request->get('host'),
            'database' => $request->get('database'),
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => $request->get('schema'),
        ];

        $client = Client::find($request->get('client_id'));

        config([
            'database.connections.import' => $importDb,
            'database.connections.tenant.schema' => $client->schema
        ]);

        if($request->get('type') == 'users')
        {
            $data = DB::connection('import')->table('users')->whereNull('deleted_at')->get();

            foreach($data as $user)
            {
                $memebership = json_decode($user->permissions, true);

                $memebership['password'] = $user->password;
                $memebership['title'] = $user->title;
                $memebership['department'] = $user->department;

                $nUser = User::firstOrNew(['email' => $user->email]);

                $nUser->first_name = $user->first_name;
                $nUser->last_name = $user->last_name;

                if($nUser->memeberships != null)
                {
                    $memeberships = $nUser->memberships;
                }
                else
                {
                    $memeberships = [];
                }

                $memeberships[$client->schema] = $memebership;

                $nUser->memberships = $memeberships;

                $nUser->save();
            }

            return 'Migrated';
        }
        else
        {
            switch ($request->get('type')) {
            case 'score':
                Schema::connection('tenant')->create($request->get('table'), function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('property_id')->unsigned();
                    $table->float('score')->nullable();
                });
                break;
            case 'data_table':
                config([
                    'database.default' => 'tenant',
                ]);

                $tableModel = new Table();
                $tableModel->setConnection('tenant');
                $table = $tableModel->where('table_name', $request->get('table'))->first();
                $table = $tableBuilder->create($table);
                $schema = $client->schema;

                $this->dispatch(new ImportDb($table, $importDb, $schema));
                return 'Queued.';
                break;
        }



            $data = DB::connection('import')->table($request->get('table'))->get();

            $data = collect($data)->map(function ($x) {
                return (array)$x;
            })->toArray();

            DB::connection('tenant')->table($request->get('table'))->insert($data);

            return 'Queued';}
    }
}
