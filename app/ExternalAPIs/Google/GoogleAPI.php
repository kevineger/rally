<?php namespace ExternalAPIs\Google;

use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_QueryRequest;
use phpRAW\phpRAW;

class GoogleAPI {

    protected $client;
    protected $reddit;
    protected $subredditsList;

    public function __construct(Google_Client $client)
    {
        $this->client = $client;
        $this->reddit = new phpRAW();
    }

    /**
     * Query the redditbigquery table with the specified query.
     *
     * @param $query
     * @param null $subreddits
     * @return mixed
     */
    public function query($query, $subreddits = null)
    {
        $bigquery = new Google_Service_Bigquery($this->client);
        $projectId = 'redditbigquery';
        $request = new Google_Service_Bigquery_QueryRequest();

        $subredditsList = '';
        if ($subreddits && is_array($subreddits))
        {
            // If multiple subreddits were specified (array)
            $subredditsList = '"' . implode('", "', $subreddits) . '"';
        } else if ($subreddits)
        {
            // If one subreddit was specified
            $subredditsList = '"' . $subreddits . '"';
        } else
        {
            // Load the default (top 5) subreddits
            $subreddits = [];
            $popular_subreddits = $this->reddit->getPopularSubreddits(5);
            // Build list of top subreddits
            foreach ($popular_subreddits->data->children as $sub)
            {
                $subreddits[] = $sub->data->display_name;
            }
            $subredditsList = '"' . implode('", "', $subreddits) . '"';
            $this->subredditsList = $subredditsList;
        }
        // Replace the default subreddits in query with specified ones
        $request->setQuery(str_replace("%subreddits", $subredditsList, config("constants.$query")));
        $response = $bigquery->jobs->query($projectId, $request);

        return $response;
    }

    public function getSubredditsList()
    {
        return $this->subredditsList;
    }
}