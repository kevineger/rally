<?php

namespace App\Http\Controllers;

use Google;
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
        $best_hours = Google::query('best_hours');
        $time_usage = Google::query('time_usage');

        $results = [];
        $results['Best Time to Post on Reddit'] = $best_hours;
//        $results['Time Usage'] = $time_usage;
        $hours = [];
        foreach ( $time_usage->getRows() as $row ) {
            $hours[$row[1]->getV()] = [];
        }
        foreach ( $time_usage->getRows() as $row ) {
//            $hours[$row[1]->getV()][$row[0]->getV()] = $row[2]->getV();
            array_push($hours[$row[1]->getV()], $row[2]->getV());
        }

//        dd($hours);

        return response()->view('big-data.index', [
            'results'    => $results,
            'time_usage' => $hours
        ]);
    }
}
