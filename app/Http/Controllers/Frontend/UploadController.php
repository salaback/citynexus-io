<?php

namespace App\Http\Controllers\Frontend;

use App\DataStore\Jobs\ProcessUpload;
use App\Http\Controllers\Controller;
use App\DataStore\Jobs\StartImport;
use App\DataStore\Model\DataSet;
use App\DataStore\Store;
use App\DataStore\Model\Upload;
use App\PropertyMgr\Model\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Facades\Datatables;

class UploadController extends Controller
{
    private $storeHelper;

    public function __construct()
    {
        $this->storeHelper = new Store();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        if($request->ajax())
            return $upload;
        else
        {
            $this->storeHelper->processUpload($upload->id);
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
     *
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

    public function process($id)
    {
        $upload = Upload::find($id);
        $this->dispatch(new ProcessUpload(config('client.id'), $upload));

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
