<?php

namespace App\Http\Controllers;

use App\Repositories\ImageRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use phpRAW\phpRAW;

class ImagesController extends Controller {

    private $image_repo;
    private $phpRaw;

    public function __construct(ImageRepository $image_repo, phpRAW $phpRAW)
    {
        $this->image_repo = $image_repo;
        $this->phpRaw = $phpRAW;
    }

    public function index()
    {
        dd($this->image_repo->analyze('https://718be87de2f7403df3e8-1d1221e10f82d636f1f5dc20a850700a.ssl.cf5.rackcdn.com/files/Golden-Retriever.jpg')->getAll());

        return "Image index";
    }

    public function analyzeSubreddit($subreddit)
    {
        // Get the most recent 50 images
        $top_listings = $this->phpRaw->getTop($subreddit, 'all', 50);
        $jpgs = [];
        // Analyze each image
        foreach ($top_listings->children as $listing)
        {
            $url = $listing->data->url;

            // If image is incompatible image type (not .jpg)
            if (substr($url, -4) == '.png' || substr($url, -4) == '.gif')
            {
                continue;
            }

            // If Imgur image in viewer (not raw .jpg file) and isnt' an album, add .jpg
            if (substr($url, -4) != '.jpg' && substr($url, -5) != '.jpeg' && strpos($url, 'http://imgur.com/a/') === false && strpos($url, 'imgur.com/') !== false)
            {
                $url .= '.jpg';
            }
            // If we do have easy access to image as jpg, add it to jpgs list
            if (substr($url, -4) == '.jpg')
            {
                $jpgs[] = $url;
            }
        }
        // Return the top prediction of each image
        // TODO: Make this a queuable job
        $predictions = [];
        foreach ($jpgs as $jpg)
        {
            $predictions[] = $this->image_repo->analyze($jpg)->getTop();
            error_log("Adding to prediction array");
        }
        dd($predictions);

        return response()->view('image.predictions', ['predictions' => $predictions]);
    }

    public function show()
    {
        return "Image show";
    }
}
