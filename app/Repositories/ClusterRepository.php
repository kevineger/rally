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
        $cluster_info = Google::query('cluster_info_posts', $subreddit);
        $matrix = $this->getMatrix($cluster_info);

        // Currently limiting return data to 100,000 rows as per the
        // google bigquery default response size
        // resource: https://cloud.google.com/bigquery/docs/data
        /*$cluster_info = Google::query('cluster_info', $subreddit);
        error_log("Finished collecting info");*/

        // Generate usable matrix from cluster info
//        $matrix = $this->getMatrix($cluster_info);

        // Write matrix to text file to be used by python script

        // Return path to text file
    }

    private function getMatrix($cluster_info)
    {
        error_log("Processing info");
        // Put the cluster info in to a usable form
        $values = [];
        $users = [];
        $links = [];
        $count = 0;
        foreach ($cluster_info->getRows() as $row)
        {
            // Save the number of times a user commented on a post
            // result[author][link_id] = count
            $values[$row[0]->getV()][$row[1]->getV()] = $row[2]->getV();

            // Save unique users
            if (!in_array($row[0]->getV(), $users))
            {
                $users[] = $row[0]->getV();
            }

            // Save unique link_ids
            if (!in_array($row[1]->getV(), $links))
            {
                $links[] = $row[1]->getV();
            }
            $count++;
        }

        error_log("Count: " . $count);
        error_log("Number of authors: " . sizeof($users));
        error_log("Number of posts: " . sizeof($links));

        error_log("Building Matrix");
        // Build up full result matrix
        $result = [];
        foreach ($users as $auth_key => $author)
        {
            foreach ($links as $link_key => $link_id)
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

        error_log("Creating json file");
        file_put_contents('/home/kevin/Downloads/matrix.json', json_encode($result));
        error_log("Json file created");

        return null;
    }
}