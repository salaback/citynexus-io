<?php

namespace App\Http\Controllers\Frontend;

use App\DataStore\Jobs\ProcessUpload;
use App\DataStore\Model\Connection;
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
use Illuminate\Support\Facades\App;
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

        if($type == 'dropbox') return $this->createDropbox($request);

        $dataset = DataSet::find($request->get('dataset_id'));
        return view('uploader.' . $type . '.create', compact('dataset'));
    }

    private function createDropbox($request)
    {
        $dataset = DataSet::find($request->get('dataset_id'));
        $connection = Connection::find($request->get('connection_id'));

        if($connection->created_by != Auth::id()) App::abort('Unauthorized');

        return view('uploader.dropbox.create', compact('dataset', 'connection'));
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

            case 'dropbox':
                return $this->storeDropbox($request);
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
                    if($field != null && $map[$field]['key'] != null && $map[$field]['key'] != '__ignore') $newfields[$field] = $map[$field];
                }

                if(count($newfields) > 0)
                {
                    $tableBuilder = new TableBuilder();
                    $tableBuilder->addToTable($uploader->dataset, $newfields);
                }
            }

            foreach($map as $key => $value)
            {
                if($value['key'] == "__ignore")
                    unset($map[$key]);
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

    public function tagSync($id)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $uploader = Uploader::find($id);
        $fields = $uploader->map;
        return view('uploader.sync.tag', compact('uploader', 'fields'));
    }

    public function primaryIdSync($id)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $uploader = Uploader::find($id);
        $fields = $uploader->map;
        return view('uploader.sync.primary_id', compact('uploader', 'fields'));
    }

    public function timestampSync($id)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $uploader = Uploader::find($id);
        $fields = $uploader->map;
        return view('uploader.sync.timestamp', compact('uploader', 'fields'));
    }

    public function removeSync($id, Request $request)
    {
        $this->authorize('citynexus', ['datasets', 'create-uploader']);

        $uploader = Uploader::find($id);
        $syncs = $uploader->syncs;
        unset($syncs[$request->get('key')]);
        $uploader->syncs = $syncs;
        $uploader->save();

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
            $uploader['settings']['sync']['created_at']['datatime'] = $uploader['settings']['created_at'];
        }

        if(isset($uploader['settings']['primary_id']))
        {
            $uploader['settings']['sync']['primary_id'][] = $uploader['settings']['primary_id'];
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

    private function storeDropbox($request)
    {

        $uploader = Uploader::create($request->all());

        $connection = Connection::find($uploader->settings['connection_id']);

        $filename = $this->getDropboxFile($uploader->settings['sampleFile'], $connection->settings['access_token']);

        $uploader->map = $this->store->analyzeFile($filename, 'spreadsheetml', true);
        $uploader->save();

        unlink($filename);
        return redirect(route('uploader.createMap', [$uploader->id]));

    }

    private function getDropboxFile($path, $token)
    {
        // build headers
        $headers = [
            "Authorization: Bearer " . $token,
            "Dropbox-API-Arg: " . json_encode(['path' => $path])
        ];

        $meta = $this->getDropboxMeta($path, $token);

        $output = $this->runCurl('https://content.dropboxapi.com/2/files/download', false, $headers);

        $filename = storage_path() . "/" . random_int(1000,99999) . '_' . $meta->name;

        $file = fopen($filename, "w");
        fwrite($file, $output);
        fclose($file);

        return $filename;
    }

    private function getDropboxMeta($path, $token)
    {

        // build headers
        $headers = [
            "Authorization: Bearer " . $token,
            "Content-Type: application/json"
        ];

        $data =  json_encode([
            'path' => $path
        ]);

        return json_decode($this->runCurl('https://api.dropboxapi.com/2/files/get_metadata', $data, $headers));
    }

    private function runCurl($url, $data = false, $headers = false)
    {
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        if($headers)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if($data)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        return $output;
    }

}