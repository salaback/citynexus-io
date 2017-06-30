<?php

namespace App\Http\Controllers\Frontend;

use App\DataStore\Jobs\ProcessUpload;
use App\DataStore\TableBuilder;
use App\Exceptions\TableBuilder\TableBuilderException;
use App\Http\Controllers\Controller;
use App\Jobs\SqlImport;
use App\Jobs\StartImport;
use App\Jobs\StartUpload;
use App\Jobs\TestJob;
use Carbon\Carbon;
use App\DataStore\DataProcessor;
use App\DataStore\Model\DataSet;
use App\DataStore\UploadHelper;
use App\DataStore\Store;
use App\DataStore\Model\Upload;
use App\DataStore\Model\Uploader;
use CityNexus\CityNexus\Table;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DataStore\Typer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UploaderController extends Controller
{

    use DispatchesJobs;

    private $uploader;
    private $store;

    public function __construct()
    {
        $this->store = new Store();
        $this->uploader = new UploadHelper();
    }

    public function index()
    {

        return view('uploader.create');
    }

    /**
     * Create a new uploader based on the source type
     *
     * @param  int  $slug
     * @return Response
     */

    public function create(Request $request)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $type = $request->get('type');
        $dataset = DataSet::find($request->get('dataset_id'));
        return view('uploader.' . $type . '.create', compact('dataset'));
    }

    public function store(Request $request)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        switch ($request->get('type'))
        {
            case 'csv':
                return $this->storeCsv($request);
                break;

            case 'sql':
                return $this->storeSql($request);
        }
    }

    public function show($id)
    {
        $this->authorize('citynexus', ['datasets', 'upload']);

        $uploader = Uploader::find($id);
        $uploads = $uploader->uploads;
        return view('uploader.types.' . $uploader->type, compact('uploader', 'uploads'));
    }


    public function createMap($uploader_id)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $uploader = Uploader::find($uploader_id);

        if($uploader->map == null)
        {
            $uploader->map = $this->store->analyzeFile($uploader->uploads->first()->source, $uploader->uploads->first()->file_type);
            $uploader->save();
        }
        return view('uploader.createSchema', compact('uploader'));
    }

    /**
     * @param Request $request
     */
    public function storeMap(Request $request)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $uploader = Uploader::find($request->get('uploader_id'));
        $map = $request->get('map');

        try
        {
            if($request->exists('new_fields'))
            {
                $newfields = [];
                foreach($request->get('new_fields') as $field)
                {
                    if($field != null && $map[$field]['key'] != null) $newfields[$field] = $map[$field];
                }

                if(count($newfields) > 0)
                {
                    $tableBuilder = new TableBuilder();
                    $tableBuilder->addToTable($uploader->dataset, $newfields);
                }
            }

            $uploader->map = $map;
            $uploader->save();

        }
        catch (TableBuilderException $e)
        {
            session()->flash('flash_warning', $e->getMessage());
            return redirect()->back();
        }

        return redirect(route('uploader.show', [$uploader->id]));

    }

    public function storeSync(Request $request)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $uploader = Uploader::find($request->get('uploader_id'));
        $uploader->addSync($request->get('sync'));
        return redirect(route('uploader.show', [$uploader->id]));
    }

    public function addressSync($id)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $uploader = Uploader::find($id);
        $fields = $uploader->map;
        return view('uploader.sync.address', compact('uploader', 'fields'));
    }

    public function entitySync($id)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $uploader = Uploader::find($id);
        $fields = $uploader->map;
        return view('uploader.sync.entity', compact('uploader', 'fields'));
    }

    public function removeSync($id, Request $request)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $uploader = Uploader::find($id);
        $syncs = $uploader->syncs;
        $target = json_decode($request->get('sync'));
        foreach($syncs as $key => $sync)
        {
            if($sync = $target)
            {
                unset($syncs[$key]);
                $uploader->syncs = $syncs;
                $uploader->save();
                return redirect()->back();
            }
        }

        session()->flash('flash_warning', 'Uh oh. Something went wrong when trying to delete that sync.');
        return redirect()->back();

    }

    public function filters($id)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $uploader = Uploader::find($id);
        $datafields = $uploader->map;
        return view('uploader.slides.filters', compact('uploader', 'datafields'));
    }

    public function testSql(Request $request)
    {
        $settings = $request->get("settings");
        config(['database.connections.target' => $settings]);

        if(\Illuminate\Support\Facades\Schema::connection('target')->hasTable($request->get('table')))
        {
            return DB::connection('target')
            ->table('information_schema.columns')
            ->where('table_schema', $settings['schema'])
            ->where('table_name', $request->get('table'))
            ->get();
        }
        else
        {
            return response(500, 'could not connect');
        }

    }

    private function storeSql(Request $request)
    {
        $uploader = $request->all();
        $uploader['map'] = $this->store->analyzeSQL($uploader['settings']);
        $uploader['file_type'] = 'sql';
        if(isset($uploader['settings']['created_at']))
        {
            $uploader['settings']['sync']['created_at'] = $uploader['settings']['created_at'];
        }

        if(isset($uploader['settings']['primary_id']))
        {
            $uploader['settings']['sync']['primary_id'] = $uploader['settings']['primary_id'];
        }

        $uploader = Uploader::create($uploader);

        return redirect(route('uploader.createMap', [$uploader->id]));
    }

    private function storeCsv(Request $request)
    {

        $uploader = Uploader::create($request->all());
        $upload = $request->get('upload');
        $upload['uploader_id'] = $uploader->id;
        $upload['user_id'] = Auth::Id();
        Upload::create($upload);

        return redirect(route('uploader.createMap', [$uploader->id]));
    }
}