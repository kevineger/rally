<?php namespace ExternalAPIs\Google;

use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;

class GoogleAPI {

    protected $client;

    public function __construct(Google_Client $client)
    {
        $this->client = $client;
    }

    /**
     * Query the redditbigquery table with the specified query.
     *
     * @param $query
     * @return mixed
     */
    public function query($query)
    {
        $bigquery = new Google_Service_Bigquery($this->client);
        $projectId = 'redditbigquery';
        $request = new Google_Service_Bigquery_QueryRequest();
        $request->setQuery(config("constants.$query"));
        $response = $bigquery->jobs->query($projectId, $request);

        return $response;
    }
}