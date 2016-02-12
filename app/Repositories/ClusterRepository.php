<?php

namespace App\Repositories;

use Google;
use Google_Client;

class ClusterRepository {

    // Google BigQuery client
    protected $client;

    public function __construct(Google_Client $client)
    {
        $this->client = $client;
    }

    public function getSubredditSubmissionHistory($subreddit)
    {
        // Currently limiting return data to 100,000 rows as per the
        // google bigquery default response size
        // resource: https://cloud.google.com/bigquery/docs/data
        $cluster_info = Google::query('cluster_info', $subreddit);
        error_log("Finished collecting info");
        $authors = Google::query('cluster_authors', $subreddit);
        error_log("Finished collecting authors");
        $link_ids = Google::query('cluster_link_ids', $subreddit);
        error_log("Finished collecting link ids");

        // Generate usable matrix from cluster info
        $matrix = $this->getMatrix($cluster_info, $authors, $link_ids);

        // Write matrix to text file to be used by python script

        // Return path to text file
    }

    private function getMatrix($cluster_info, $authors, $link_ids)
    {
        error_log("Processing author list");
        // Put the author info in to a usable form
        $author_list = [];
        foreach ($authors->getRows() as $row)
        {
            $author_list[] = $row[0]->getV();
        }

        error_log("Processing link_id list");
        // Put the author info in to a usable form
        $link_ids_list = [];
        foreach ($link_ids->getRows() as $row)
        {
            $link_ids_list[] = $row[0]->getV();
        }

        error_log("Processing value list");
        // Put the cluster info in to a usable form
        $values = [];
        foreach ($cluster_info->getRows() as $row)
        {
            // result[author][link_id] = count
            $values[$row[0]->getV()][$row[1]->getV()] = $row[2]->getV();
        }

        error_log("Building resulting matrix");
        error_log("Number of authors: " . sizeof($author_list));
        error_log("Number of posts: " . sizeof($link_ids_list));

        // Build up full result matrix
        $result = [];
        foreach ($author_list as $auth_key => $author)
        {
            foreach ($link_ids_list as $link_key => $link_id)
            {
                // If an author commented on a link
                if (array_key_exists($link_id, $values[$author]))
                {
                    // Set the [author][link_id] = count
                    $result[$auth_key][$link_key] = (int)$values[$author][$link_id];
                } else
                {
                    $result[$auth_key][$link_key] = 0;
                }
            }
        }

//        error_log("Dumping resulting matrix");
//        dd($result);

        error_log("Creating json file");
        file_put_contents('/home/kevin/Downloads/matrix.json', json_encode($result));
        error_log("Json file created");

        return null;
    }
}