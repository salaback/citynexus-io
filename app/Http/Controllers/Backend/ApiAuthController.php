<?php

namespace App\Http\Controllers\Backend;

use App\Client;
use App\DataStore\Model\Connection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiAuthController extends Controller
{
    public function dropbox(Request $request)
    {

        $state = json_decode($request->get('state'));

        $client = Client::find($state->client_id);

        $data = [
            'code' => $request->get('code'),
            'client_id' => 'yn3kwol8tef5ozi',
            'client_secret' => env('DROPBOX_SECRET'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'http://localhost:8000/response/dropbox'
        ];

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "https://api.dropboxapi.com/oauth2/token");

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        $settings = \GuzzleHttp\json_decode($output, true);

        $connection = Connection::create([
           'type' => 'dropbox',
            'created_by' => $state->created_by,
            'settings' => $settings
        ]);

        return redirect('http://' . $client->domain . '/uploader/create?dataset_id=' . $state->dataset_id . '&type=dropbox&connection_id=' . $connection->id);
    }
}
