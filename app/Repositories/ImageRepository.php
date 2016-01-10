<?php

namespace App\Repositories;

use App\Image;

class ImageRepository {
    private $client;
    private $response;
    protected $results;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'localhost:5000/tensor/api/v1.0/']);
    }

    /**
     * Return json response from Tensorflow API.
     *
     * @param $url
     * @return mixed
     */
    public function analyze($url)
    {
        // If image has been cached, load cached analysis.
        $image = Image::where('url', $url)->first();
        if ( $image ) {
            $this->results = json_decode($image->analysis);

            return $this;
        }

        // Perform analysis and save results.
        $this->response = $this->client->post("images", ['json' => ['url' => $url]]);
        $this->results = json_decode($this->response->getBody(), true);
        Image::create([
            'url'      => $url,
            'analysis' => json_encode($this->results)
        ]);

        return $this;
    }

    /**
     * Get all results and their likelihoods and return as associative array.
     *
     * @return mixed
     */
    public function getAll()
    {
        dd($this->results);
        arsort($this->results);

        return $this->results;
    }

    /**
     * Get's the top result from the analysis.
     * Optionally specify top number of results.
     *
     * @param int $num
     * @return array
     */
    public function getTop($num = 1)
    {
        $results = $this->getAll();

        return array_slice($results, 0, $num);
    }

    /**
     * Get likely results (greater than specified threshold).
     *
     * @param float $prob
     * @return array
     */
    public function getLikely($prob = 0.10)
    {
        $results = $this->getAll();

        $likely = collect($results)->filter(function ($probability) use ($prob) {
            return $probability > $prob;
        });

        return $likely->all();
    }


}