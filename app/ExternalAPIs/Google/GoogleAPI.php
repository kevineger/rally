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
//        $request->setQuery('SELECT DAYOFWEEK(SEC_TO_TIMESTAMP(created - 60*60*5)) as sub_dayofweek, HOUR(SEC_TO_TIMESTAMP(created - 60*60*5)) as sub_hour, SUM(IF(score >= 3000, 1, 0)) as num_gte_3000, FROM [fh-bigquery:reddit_posts.full_corpus_201509] GROUP BY sub_dayofweek, sub_hour ORDER BY sub_dayofweek, sub_hour');
        $request->setQuery($query);
        $response = $bigquery->jobs->query($projectId, $request);

        return $response->getRows();
    }
}