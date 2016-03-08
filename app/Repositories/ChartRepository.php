<?php

namespace App\Repositories;

use Khill\Lavacharts\Configs\DataTable;
use Lava;

class ChartRepository {

    public function getFancyChart()
    {
        $stocksTable = Lava::DataTable();

        $stocksTable->addDateColumn('Day of Month')
            ->addNumberColumn('Projected')
            ->addNumberColumn('Official');

        // Random Data For Example
        for ($a = 1; $a < 30; $a++)
        {
            $rowData = array(
                "2014-8-$a", rand(800, 1000), rand(800, 1000)
            );

            $stocksTable->addRow($rowData);
        }

        return $stocksTable;
    }

    public function getTimeUsage($time_usage)
    {
        $subreddit_list = [];
        $hours = [];
        foreach ($time_usage->getRows() as $row)
        {
            // Initialize hours array 0-18
            $hours[$row[1]->getV()] = [];
            if (!in_array($row[0]->getV(), $subreddit_list))
            {
                $subreddit_list[] = $row[0]->getV();
            }
        }
        // Break the subreddits from the BigTable names
        // ie: make user friendly name you would see on r/____
        $nameClean = function ($value)
        {
            return explode('-', $value)[1];
        };
        $subreddit_list = array_map($nameClean, $subreddit_list);

        // Since some subreddits are so unpopular the query doesn't pick them up,
        // we have to loop manually and do the corresponding checks for consistent
        // entry of hours...
        // For each subreddit
        // Add the hours of each subreddit to the corresponding hour (0-23)
        $rows = $time_usage->getRows();
        $offset = 0;
//        dd($rows);
        for ($i = 0; $i < sizeof($subreddit_list); $i++)
        {
            // For each hour (0-23)
            for ($j = 0; $j <= 23; $j++)
            {
                // Check if hour at row being evaluated is consistent with current hour $j
                // If it is not, increase the offset check and push 0 for that hour/sub.
                if (!array_key_exists($i * 24 + $j - $offset, $rows) || $rows[$i * 24 + $j - $offset][1]->getV() != $j)
                {
                    $offset++;
                    array_push($hours[$j], "0");
                } else
                {
                    // Normally add the hour/value.
                    array_push($hours[$j], $rows[$i * 24 + $j - $offset][2]->getV());
                }
            }
        }
//        dd($hours);

        $usageTable = Lava::DataTable();
        $usageTable->addNumberColumn('Hour');

        for ($i = 0; $i < sizeof($hours[0]); $i++)
        {
            $usageTable->addNumberColumn($subreddit_list[$i]);
        }
        foreach ($hours as $key => $hour)
        {
            $row = [];
            $row[] = $key;
            for ($i = 0; $i < sizeof($hours[0]); $i++)
            {
                // For the rare case that there is zero hour activity
                if (!isset($hour[$i]))
                {
                    $hour[$i] = "0";
                }
                $row[] = $hour[$i];
            }
            $usageTable->addRow($row);
        }

        return $usageTable;
    }

}