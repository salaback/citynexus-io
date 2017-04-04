<?php

namespace App\Http\Controllers;

use App\Jobs\SqlImport;
use CityNexus\DataStore\DataSet;
use CityNexus\DataStore\Store;
use CityNexus\DataStore\Upload;
use CityNexus\DataStore\Uploader;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CityNexus\DataStore\Typer;

class UploaderController extends Controller
{


    public function __construct()
    {
        $this->store = new Store();
    }

    public function index()
    {
        return view('uploader.create');
    }

    /**cd
     * Show the profile for the given user.
     *
     * @param  int  $slug
     * @return Response
     */

    public function create()
    {
        return view('uploader.create');
    }

    public function store(Request $request)
    {
        $uploader = Uploader::create($request->all());

        return $uploader;
    }

    public function show($id)
    {
        $uploader = Uploader::find($id);
        return view('uploader.types.' . $uploader->type, compact('uploader'));
    }


    public function schema(Request $request)
    {
        $uploader = Uploader::find($request->get('uploader_id'));

        // if post include a data map save to the uploader.
        if($request->exists('map'))
        {
            $uploader->map = $request->get('map');
            return redirect(route('uploader.show', [$uploader->id]));
        } else{

            // TODO add method to add a map which maps to existing data fields

            // else process the data upload to return a draft map

            $upload = Upload::find($request->get('upload_id'));
            $table = $this->store->analyizeFile($upload->source, $upload->file_type);
            if($uploader->dataset->schema == null) { $uploader->dataset->schema = $table; $uploader->dataset->save(); }
            $uploader->map = $table;
            $uploader->save();
            $dataset = DataSet::find($request->get('dataset_id'));
            if($dataset->schema != null)
            {
                return view('uploader.slides.datafields', compact('table', 'uploader', 'dataset'));
            }

            return view('uploader.slides.datafields', compact('table', 'uploader'));
        }

    }

    public function addressSync($id)
    {
        $uploader = Uploader::find($id);
        $fields = $uploader->map;
        return view('uploader.sync.address', compact('uploader', 'fields'));
    }

    public function entitySync($id)
    {
        $uploader = Uploader::find($id);
        $fields = $uploader->map;
        return view('uploader.sync.entity', compact('uploader', 'fields'));
    }

    public function filters($id)
    {
        $uploader = Uploader::find($id);
        $datafields = $uploader->map;
        return view('uploader.slides.filters', compact('uploader', 'datafields'));
    }


    public function post(Request $request)
    {

        switch ($request->get('slug'))
        {
            case 'import-sql':
                $this->dispatch(new SqlImport(config('database.connections.tenant.schema'), $request->get('uploader_id'), $request->get('settings')));
                session(['flash_success' => 'SQL Import has been queued.']);
                return redirect()->back();
                break;

            case 'create':
                $uploader = Uploader::create($request->all());
                return response()->json($uploader);
                break;

            case 'slide':
                return view('uploader.slides.' . $request->get('slide'));
                break;

            case 'upload':
                $upload = $request->all();

                $upload['user_id'] = Auth::id();
                $upload = Upload::create($upload);
                return response()->json($upload);
                break;

            case 'set-data-type':

                // load dataset
                $dataset = $request->all();

                // create table name;
                $dataset['table_name'] = $this->store->tableNameMaker($dataset['name']);

                // save dataset
                $dataset = DataSet::create($dataset);

                // update upload with dataset info
                $upload = Upload::find($request->get('upload_id'));
                $upload->dataset_id = $dataset->id;
                $upload->save();

                // Update uploader with dataset info
                $uploader = Uploader::find($request->get('uploader_id'));
                $uploader->dataset_id = $dataset->id;
                $uploader->save();

                // return dataset id
                return response()->json($dataset);
                break;

            case 'get_filters':

                $uid = str_random(16);
                switch ($request->get('data_type'))
                {
                    case 'string':
                        return view('uploader.slides.filters.string', compact('uid'));
                        break;

                    default: return response('Filter not found', 404);
                }

                break;

            case 'add_filter':
                $uid = str_random(16);
                $key = $request->get('key');
                return view('uploader.slides.filters.' . $request->get('filter'), compact('key', 'uid'));
                break;

            case 'save_filter':
                $filters = $request->get('filter');
                return view('uploader.slides.filters.filter-preview', compact('filters'));
                break;

            case 'commit_filters':
                $uploader = Uploader::find($request->get('uploader_id'));
                $uploader->filters = $request->get('filters');
                $uploader->save();
                return redirect(route('uploader.show', [$uploader->id]));
                break;

            case 'datafields':
                $uploader = Uploader::find($request->get('uploader_id'));
                $upload = Upload::find($request->get('upload_id'));
                $dataset_id = $request->get('dataset_id');
                $table = $this->store->analyizeFile($upload->source);
                $uploader->map = $table;
                $uploader->save();
                $table = json_decode($uploader->map);

                return view('uploader.slides.datafields', compact('table', 'dataset_id', 'uploader'));
                break;

            case 'save_schema':
                $dataset = DataSet::find($request->get('dataset_id'));
                $dataset->schema = $request->get('map');
                $dataset->save();
                return redirect(route('uploader.show', [$request->get('uploader_id')]));
                break;

            case 'sync':
                $uploader = Uploader::find($request->get('uploader_id'));
                $uploader->addSync($request->get('sync'));
                return redirect(route('uploader.show', [$uploader->id]));
                break;

            default: return response('Slide not found', 404);
        }
    }

}