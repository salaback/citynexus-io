<?php

namespace App\Http\Controllers\Frontend;

use App\DataStore\Importer;
use App\DataStore\Jobs\ProcessData;
use App\Http\Controllers\Controller;
use App\DataStore\Store;
use App\DataStore\Model\Upload;
use App\PropertyMgr\Model\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    private $storeHelper;

    public function __construct()
    {
        $this->storeHelper = new Store();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $upload = $request->all();
        $upload['user_id'] = Auth::id();
        $upload = Upload::create($upload);

        $this->dispatch(new Upload($upload->id));

        if($request->ajax())
        {
            return $upload;
        }
        else
        {
            return redirect(route('uploader.show', $upload->uploader->id));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Upload $upload)
    {
        $properties = Property::find($upload->new_property_ids);
        $data = DB::table($upload->uploader->dataset->table_name)->where('upload_id', $upload->id)->get();
        return view('upload.show', compact('upload', 'properties', 'data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $importer = new Importer();

        $upload = Upload::find($id);

        $importer->clearUpload($upload);

        $upload->count = 0;
        $upload->processed_at = null;
        $upload->deleted_at = Carbon::now();
        $upload->save();

        if($request->ajax())
        {
            return 'deleted';
        }
        else
        {
            session()->flash('flash_success', 'Upload has been removed.');
            return redirect(route('uploader.show', $upload->uploader->id));
        }
    }

    public function process($id)
    {

        $upload = Upload::find($id);

        $this->dispatch(new ProcessData($upload->id));

        return 'queued';
    }

    public function post(Request $request)
    {
        switch ($request->get('slug'))
        {
            case 'csv_upload':
                $upload = Upload::create($request->all());
                break;
        }
    }
}
