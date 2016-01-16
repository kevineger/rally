<?php

namespace App\Repositories;

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
        $hours = [];
        foreach ($time_usage->getRows() as $row)
        {
            $hours[$row[1]->getV()] = [];
        }
        foreach ($time_usage->getRows() as $row)
        {
            array_push($hours[$row[1]->getV()], $row[2]->getV());
        }

        $usageTable = Lava::DataTable();
        $usageTable->addNumberColumn('Hour')
            ->addNumberColumn('Sub1')
            ->addNumberColumn('Sub2')
            ->addNumberColumn('Sub3')
            ->addNumberColumn('Sub4')
            ->addNumberColumn('Sub5');

        foreach ($hours as $key => $hour)
        {
            $usageTable->addRow([$key, $hour[0], $hour[1], $hour[2], $hour[3], $hour[4]]);
        }

        return $usageTable;
    }

}