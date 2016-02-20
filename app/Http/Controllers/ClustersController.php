<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Repositories\ClusterRepository;
use Google_Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ClustersController extends Controller {

    protected $client;
    protected $cluster;

    public function __construct()
    {
        // Initialize GoogleBigQuery client
        $this->client = new Google_Client();
        $this->client->useApplicationDefaultCredentials();
        $this->client->addScope(\Google_Service_Bigquery::BIGQUERY);

        $this->cluster = new ClusterRepository($this->client);
    }

    /**
     * Display the view to specify subreddit.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('cluster.index');
//        $this->dispatchFrom('App\Jobs\ClusterSubreddit', $request);
    }

    /**
     * Show the clustering of a subreddit.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return response()->view('cluster.show', ['subreddit' => $request->get('subreddit')]);
    }

    /**
     * Respond to AJAX requests to cluster a subreddit and return the path to image of clustering.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clusterSubreddit(Request $request)
    {
        $subreddit = $request->get('subreddit');

        // Look for cached cluster
        $cluster_image = Cluster::where('name', $subreddit)->first();

        // Else, perform new clustering
        if($cluster_image == null) {
            // Perform the clustering and return the path to image.
            $path = $this->cluster->getSubredditSubmissionHistory($subreddit);

            // Save a copy of image to public folder
            $cluster_image = Cluster::named($subreddit);
            $cluster_image->move($path);
        }

        return response()->json(['path_to_image' => $cluster_image->path]);
    }
}
