<?php

namespace App\Jobs;

use App\Jobs\Job;
use Google_Client;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\ClusterRepository;

class ClusterSubreddit extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    protected $client;
    protected $cluster;
    // The subreddit we are performing the cluster on
    protected $subreddit;

    /**
     * Create a new job instance.
     * @param $subreddit
     */
    public function __construct($subreddit)
    {
        // Initialize GoogleBigQuery client
        $this->client = new Google_Client();
        $this->client->useApplicationDefaultCredentials();
        $this->client->addScope(\Google_Service_Bigquery::BIGQUERY);

        $this->cluster = new ClusterRepository($this->client);

        $this->subreddit = $subreddit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Retrive, generate and output to text file information needed to cluster subreddits
        $this->cluster->getSubredditSubmissionHistory($this->subreddit);

        // Cluster the subreddit by calling the python script with path to text file (data)
    }
}
