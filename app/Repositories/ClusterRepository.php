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

        error_log("Creating json file");
        $json_location = env('JSON_MATRIX_LOCATION');
        file_put_contents($json_location, json_encode($matrix));
        error_log("Json file created");

        $path_to_image = [];
        $cluster_script = env('CLUSTER_SCRIPT');
        exec("python $cluster_script", $path_to_image);

        return $path_to_image[0];
    }

    private function getMatrix($cluster_info)
    {
        error_log("Processing info");
        // Put the cluster info in to a usable form
        $values = [];
        $users = [];
        $links = [];
        $countAuthors = 0;
        foreach ($cluster_info->getRows() as $row)
        {
            // Save the number of times a user commented on a post
            // result[author][link_id] = count
            $values[$row[0]->getV()][$row[1]->getV()] = $row[2]->getV();

            // Save unique users
            if (!in_array($row[0]->getV(), $users))
            {
//                if($countAuthors == 1074) {
//                    dd($row);
//                }
                $users[] = $row[0]->getV();
                $countAuthors++;
            }

            // Save unique link_ids
            if (!in_array($row[1]->getV(), $links))
            {
                $links[] = $row[1]->getV();
            }
        }

        error_log("countAuthors: " . $countAuthors);
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

        return $result;
    }
}