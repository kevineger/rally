<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Repositories\ClusterRepository;
use Google_Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use phpRAW\phpRAW;

class SubredditController extends Controller {

    protected $client;
    protected $cluster;
    protected $phpraw;

    public function __construct()
    {
        // Initialize GoogleBigQuery client
        $this->client = new Google_Client();
        $this->client->useApplicationDefaultCredentials();
        $this->client->addScope(\Google_Service_Bigquery::BIGQUERY);

        $this->phpraw = new phpRAW();

        $this->cluster = new ClusterRepository($this->client);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('subreddit.index', [
            'tagline' => 'View information about a specific subreddit'
        ]);
    }

    /**
     * Show the clustering of a subreddit.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $subreddit = $request->get('subreddit');

        $about = $this->phpraw->aboutSubreddit($subreddit);

        return response()->view('subreddit.show', [
            'subreddit' => $subreddit,
            'about'     => $about->data,
            'tagline'   => 'A look at /r/' . $subreddit
        ]);
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
        if ( $cluster_image == null ) {
            // Perform the clustering and return the path to image.
            $path = $this->cluster->getSubredditSubmissionHistory($subreddit);

            // Save a copy of image to public folder
            $cluster_image = Cluster::named($subreddit);
            $cluster_image->move($path);
        }

        return response()->json(['path_to_image' => $cluster_image->path]);
    }

    /**
     * Test method for ensuring reclustering of subreddit.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceRecluster(Request $request)
    {
        $subreddit = $request->get('subreddit');

        // Perform the clustering and return the path to image.
        $this->cluster->getSubredditSubmissionHistory($subreddit);
    }
}
