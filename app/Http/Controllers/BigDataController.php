<?php

namespace App\Http\Controllers;

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
        $bigquery = new Google_Service_Bigquery($this->client);
        $projectId = 'redditbigquery';
        $request = new Google_Service_Bigquery_QueryRequest();
//        $request->setQuery('SELECT TOP(corpus, 10) as title, COUNT(*) as unique_words ' .
//            'FROM [publicdata:samples.shakespeare]');
        $request->setQuery('SELECT DAYOFWEEK(SEC_TO_TIMESTAMP(created - 60*60*5)) as sub_dayofweek, HOUR(SEC_TO_TIMESTAMP(created - 60*60*5)) as sub_hour, SUM(IF(score >= 3000, 1, 0)) as num_gte_3000, FROM [fh-bigquery:reddit_posts.full_corpus_201509] GROUP BY sub_dayofweek, sub_hour ORDER BY sub_dayofweek, sub_hour');
        $response = $bigquery->jobs->query($projectId, $request);
        $rows = $response->getRows();

        dd($rows);

        return 'Hit Data Index';
    }
}
