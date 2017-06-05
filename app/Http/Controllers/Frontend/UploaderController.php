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
        $type = $request->get('type');
        $dataset = DataSet::find($request->get('dataset_id'));
        return view('uploader.' . $type . '.create', compact('dataset'));
    }

    public function store(Request $request)
    {
        switch ($request->get('type'))
        {
            case 'csv':
                return $this->storeCsv($request);
                break;
        }
    }

    public function show($id)
    {
        $uploader = Uploader::find($id);
        $uploads = $uploader->uploads;
        return view('uploader.types.' . $uploader->type, compact('uploader', 'uploads'));
    }


    public function createMap($upload_id)
    {
        $upload = Upload::find($upload_id);
        $uploader = $upload->uploader;
        if($uploader->map == null)
        {
            $uploader->map = $this->store->analyizeFile($upload->source, $upload->file_type);
            $uploader->save();
        }
        return view('uploader.createSchema', compact('uploader'));
    }

    /**
     * @param Request $request
     */
    public function storeMap(Request $request)
    {

        $uploader = Uploader::find($request->get('uploader_id'));
        $map = $request->get('map');

        try
        {
            if($request->exists('new_fields'))
            {
                $newfields = [];
                foreach($request->get('new_fields') as $field)
                {
                    if($field != null) $newfields[$field] = $map[$field];
                }

                $tableBuilder = new TableBuilder();
                $tableBuilder->addToTable($uploader->dataset, $newfields);
            }

            $uploader->map = $map;
            $uploader->save();

        }
        catch (TableBuilderException $e)
        {
            session()->flash('warning', $e->getMessage());
            return redirect()->back();
        }

        return redirect(route('uploader.show', [$uploader->id]));

    }

    public function storeSync(Request $request)
    {
        $uploader = Uploader::find($request->get('uploader_id'));
        $uploader->addSync($request->get('sync'));
        return redirect(route('uploader.show', [$uploader->id]));
    }

//    public function schema(Request $request)
//    {
//        $uploader = Uploader::find($request->get('uploader_id'));
//
//        // if post include a data map save to the uploader.
//        if($request->exists('map'))
//        {
//            $uploader->map = $request->get('map');
//
//            return redirect(route('uploader.show', [$uploader->id]));
//        } else{
//
//            // TODO add method to add a map which maps to existing data fields
//
//            // else process the data upload to return a draft map
//
//            $upload = Upload::find($request->get('upload_id'));
//            $table = $this->store->analyizeFile($upload->source, $upload->file_type);
//            if($uploader->dataset->schema == null) { $uploader->dataset->schema = $table; $uploader->dataset->save(); }
//            $uploader->map = $table;
//            $uploader->save();
//            $dataset = DataSet::find($request->get('dataset_id'));
//            if($dataset->schema != null)
//            {
//                return view('uploader.slides.datafields', compact('table', 'uploader', 'dataset', 'upload'));
//            }
//
//            return view('uploader.slides.datafields', compact('table', 'uploader', 'upload'));
//        }
//
//    }

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

    public function removeSync($id, Request $request)
    {
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
        $uploader = Uploader::find($id);
        $datafields = $uploader->map;
        return view('uploader.slides.filters', compact('uploader', 'datafields'));
    }

    private function storeCsv(Request $request)
    {
        $uploader = Uploader::create($request->all());
        $upload = $request->get('upload');
        $upload['uploader_id'] = $uploader->id;
        $upload['user_id'] = Auth::Id();
        $upload = Upload::create($upload);

        return redirect(route('uploader.createMap', [$upload->id]));
    }

//
//    public function post(Request $request)
//    {
//
//        switch ($request->get('slug'))
//        {
//            case 'import-sql':
//                dispatch(new StartImport(config('client.id'), 'sql', $request->get('uploader_id')));
//                session(['flash_success' => 'SQL Import has been queued.']);
//                Log::info('Got to the end');
//                return redirect()->back();
//                break;
//
//            case 'create':
//                $uploader = Uploader::create($request->all());
//                return response()->json($uploader);
//                break;
//
//            case 'slide':
//                return view('uploader.slides.' . $request->get('slide'));
//                break;
//
//            case 'upload':
//                $upload = $request->all();
//                $upload['user_id'] = Auth::id();
//                $upload = Upload::create($upload);
//                return response()->json($upload);
//                break;
//
//            case 'set-data-type':
//
//                // load dataset
//                $dataset = $request->all();
//
//                // create table name;
//                $dataset['table_name'] = $this->store->tableNameMaker($dataset['name']);
//
//                // save dataset
//                $dataset = DataSet::create($dataset);
//
//                // update upload with dataset info
//                $upload = Upload::find($request->get('upload_id'));
//                $upload->dataset_id = $dataset->id;
//                $upload->save();
//
//                // Update uploader with dataset info
//                $uploader = Uploader::find($request->get('uploader_id'));
//                $uploader->dataset_id = $dataset->id;
//                $uploader->save();
//
//                // return dataset id
//                return response()->json($dataset);
//                break;
//
//            case 'get_filters':
//
//                $uid = str_random(16);
//                switch ($request->get('data_type'))
//                {
//                    case 'string':
//                        return view('uploader.slides.filters.string', compact('uid'));
//                        break;
//
//                    default: return response('Filter not found', 404);
//                }
//
//                break;
//
//            case 'add_filter':
//                $uid = str_random(16);
//                $key = $request->get('key');
//                return view('uploader.slides.filters.' . $request->get('filter'), compact('key', 'uid'));
//                break;
//
//            case 'save_filter':
//                $filters = $request->get('filter');
//                return view('uploader.slides.filters.filter-preview', compact('filters'));
//                break;
//
//            case 'commit_filters':
//                $uploader = Uploader::find($request->get('uploader_id'));
//                $uploader->filters = $request->get('filters');
//                $uploader->save();
//                return redirect(route('uploader.show', [$uploader->id]));
//                break;
//
//            case 'datafields':
//                $uploader = Uploader::find($request->get('uploader_id'));
//                $upload = Upload::find($request->get('upload_id'));
//                $dataset_id = $request->get('dataset_id');
//                $table = $this->store->analyizeFile($upload->source);
//                $uploader->map = $table;
//                $uploader->save();
//                $table = json_decode($uploader->map);
//
//                return view('uploader.slides.datafields', compact('table', 'dataset_id', 'uploader'));
//                break;
//
//            case 'save_schema':
//                $dataset = DataSet::find($request->get('dataset_id'));
//                $dataset->schema = $request->get('map');
//                $dataset->save();
//                return redirect(route('uploader.show', [$request->get('uploader_id')]));
//                break;
//
//            case 'sync':
//                $uploader = Uploader::find($request->get('uploader_id'));
//                $uploader->addSync($request->get('sync'));
//                return redirect(route('uploader.show', [$uploader->id]));
//                break;
//
//            default: return response('Slide not found', 404);
//        }
//    }

}