<?php

namespace App\Http\Controllers;

use App\Repositories\ChartRepository;
use Google;
use Google_Service_Bigquery;
use Illuminate\Http\Request;
use App\Http\Requests;
use Google_Client;
use Khill\Lavacharts\Charts\Chart;
use Khill\Lavacharts\Configs\TextStyle;
use Lava;
use phpRAW\phpRAW;


class BigDataController extends Controller {

    protected $client;
    protected $chart_data;

    public function __construct(ChartRepository $chart)
    {
        $this->client = new Google_Client();
        $this->client->useApplicationDefaultCredentials();
        $this->client->addScope(\Google_Service_Bigquery::BIGQUERY);

        $this->chart_data = $chart;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subreddits = Google::query('subreddits');
        $best_hours = Google::query('best_hours');
        $time_usage = Google::query('time_usage');
//        dd($time_usage);

        $results = [];
        $results['Best Time to Post on Reddit'] = $best_hours;

        // Generate Time Usage chart
        $chart = Lava::LineChart('myFancyChart');
        $chart->title("Activity Over Time");
        $chart->titleTextStyle(new TextStyle(['fontSize' => 20]));
        $chart->height(500);
        $chart->datatable($this->chart_data->getTimeUsage($time_usage));


        return response()->view('big-data.index', [
            'tagline'      => 'What\'s going on in the big picture?',
            'results'      => $results,
            'subreddits'   => $subreddits,
            'default_vals' => Google::getSubredditsList()
        ]);
    }

    public function updateChart(Request $request)
    {
        $time_usage = Google::query('time_usage', $request->input('subreddits'));
        /*$chart = Lava::LineChart('myFancyChart');
        $chart->title("Activity Over Time IT CHANGED");
        $chart->height(500);
        $chart->datatable($this->chart_data->getTimeUsage($time_usage));*/

        return $this->chart_data->getTimeUsage($time_usage)->toJson();
    }
}
