<?php

namespace App\Http\Controllers;

use ExternalAPIs\Google\GoogleAPI;
use Google;
use Google_Service_Bigquery_QueryRequest;
use App\Http\Controllers\Controller;
use Google_Service_Bigquery;
use Illuminate\Http\Request;
use App\Http\Requests;
use Google_Client;

class BigDataController extends Controller {

    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->useApplicationDefaultCredentials();
        $this->client->addScope(\Google_Service_Bigquery::BIGQUERY);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Google::query('some_query');

        $data = [];
        foreach ($results as $row)
        {
            foreach ($row['f'] as $field)
            {
                $data[] = $field['v'];
            }
        }

        dd($data);

        return 'Hit Data Index';
    }
}
