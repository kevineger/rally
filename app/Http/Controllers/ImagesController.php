<?php

namespace App\Http\Controllers;

use App\Repositories\ImageRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

class ImagesController extends Controller {
    private $image_repo;

    public function __construct(ImageRepository $image_repo)
    {
        $this->image_repo = $image_repo;
    }

    public function index()
    {
        dd($this->image_repo->analyze('https://718be87de2f7403df3e8-1d1221e10f82d636f1f5dc20a850700a.ssl.cf5.rackcdn.com/files/Golden-Retriever.jpg')->getAll());

        return "Image index";
    }

    public function show()
    {
        return "Image show";
    }
}
