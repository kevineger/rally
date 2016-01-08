<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BigDataController extends Controller {
    protected $client;
    public function __construct()
    {
        $this->client = new \Google_Client();
        $this->client->setApplicationName("Client_Library_Examples");
        $this->client->setDeveloperKey("AIzaSyA6YB8rZdiTaL0m-GqKowGtTaQwrXKfIy4");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $youtube = new \Google_Service_YouTube($this->client);

        $searchResponse = $youtube->search->listSearch('id,snippet', array(
            'q' => 'cat',
            'maxResults' => 5,
        ));

        dd($searchResponse);
        return 'hit bigdatacon';
    }
}
