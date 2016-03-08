<?php

namespace App;

use Image;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\ClusterImage
 *
 */
class Cluster extends Model {

    protected $baseDir = 'photos';

    protected $fillable = [
        'path',
    ];

    /**
     * Build a new cluster photo instance.
     *
     * @param $subreddit
     * @return mixed
     */
    public static function named($subreddit)
    {
        $cluster = new Cluster;
        $cluster->name = $subreddit;
        $cluster->saveAs($subreddit);
        $cluster->save();

        return $cluster;
    }

    protected function saveAs($subreddit)
    {
        // Generate a name for the image
        $name = $subreddit . "_" . Carbon::now()->timestamp;

        $this->path = sprintf("%s/%s.png", $this->baseDir, $name);

        return $this;
    }

    public function move($original_path)
    {
        // Move the image from location outputted by script to public path
        Image::make($original_path)->save(public_path($this->path));
    }
}
