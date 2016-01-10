<?php namespace ExternalAPIs\Google;

use Google_Client;
use Google_Service_Bigquery;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('google', function ()
        {
            $client = new Google_Client();
            $client->useApplicationDefaultCredentials();
            $client->addScope(Google_Service_Bigquery::BIGQUERY);

            return new GoogleAPI($client);
        });
    }
}