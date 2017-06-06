<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Events\UserCreated;
use App\Exceptions\TableBuilder\CreateTableBuilderException;
use App\Jobs\ImportData;
use App\Jobs\ImportDb;
use App\Notifications\AddedToNewOrganization;
use App\Services\MultiTenant;
use App\User;
use CityNexus\CityNexus\TableBuilder;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Schema\Blueprint;
use CityNexus\CityNexus\Table;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
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
            'client.name' => 'required|max:255',
            'client.domain' => 'required|max:50',
            'user.first_name' => 'required|max:255',
            'user.last_name' => 'required|max:255',
            'user.email' => 'required|email|max:255'
        ]);

        try
        {
            $client = $multiTenant->createClient($request->get('client')['name'], $request->get('client')['domain']);

            $this->createOwnerUser($client, $request->get('user'));

            session()->flash('flash_success', 'Client created.');
        }
        catch (CreateTableBuilderException $e)
        {
            session()->flash('flash_warning', $e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
        catch (\Exception $e)
        {
            session()->flash('flash_warning', 'Uh oh, something went wrong. Error Code 6145');
            return redirect()->back()->withInput($request->all());
        }

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
     * @param Client $client
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Client $client)
    {
        DB::statement('DROP SCHEMA ' . $client->schema . ' CASCADE');

        $client->delete();

        return "Deleted";
    }

    public function resetDb($id, MultiTenant $multiTenant)
    {
        if($multiTenant->resetDb($id)) return response(200);
        else return response(500);
    }

    public function migrateDb($id, MultiTenant $multiTenant)
    {
        if($multiTenant->migrate(Client::find($id)) == 'migrated')
        {
            session()->flash('flash_success', "Client successfully migrated.");
            return redirect()->back();
        }
        else
        {
            session()->flash('flash_warning', "Uh oh. Client did not successfully migrate.");
        }
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

            $users = [];
            foreach($data as $user)
            {
                $membership = json_decode($user->permissions, true);

                $membership['password'] = $user->password;
                $membership['title'] = $user->title;
                $membership['department'] = $user->department;

                $nUser = User::firstOrNew(['email' => $user->email]);

                $nUser->first_name = $user->first_name;
                $nUser->last_name = $user->last_name;

                if($nUser->memberships != null)
                {
                    $memberships = $nUser->memberships;
                }
                else
                {
                    $memberships = [];
                }

                $memberships[$client->schema] = $membership;

                $nUser->memberships = $memberships;

                $nUser->save();

                $users[$user->id] = $nUser->id;
            }

            $settings = $client->settings;
            $settings['user_ids'] = $users;
            $client->settings = $settings;
            $client->save();

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

                $this->dispatch(new ImportDb($request->get('table'), $importDb, $client->schema));

            return 'Queued';}
    }

    public function upgrade($id)
    {
        Artisan::queue('citynexus:upgrade', ['client_id' => $id]);
        session('flash_success', 'Client queued for upgrade.');
        return redirect()->back();
    }

    public function createOwnerUser($client, $user)
    {

        // Get user if they already exist by email
        $userModel = User::firstOrNew(['email' => $user['email']]);

        if(!$userModel->exists)
        {
            // If user is new, add name and temp password
            $userModel->first_name = $user['first_name'];
            $userModel->last_name = $user['last_name'];
            $userModel->password = str_random(24);
            $userModel->save();

            $userModel->addMembership($client->domain, ['account_owner' => true]);

            event(new UserCreated($userModel));
        }
        else
        {
            $userModel->addMembership($client->domain, ['account_owner' => true]);

            $userModel->notify(new AddedToNewOrganization($client));
        }


        return $userModel;
    }
}
