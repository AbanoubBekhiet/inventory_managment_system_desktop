<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;
use Google\Client as GoogleClient;
use Google\Service\Drive;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Storage::extend('google', function ($app, $config) {
            $client = new GoogleClient();
            $client->setAuthConfig($config['credentials']);
            $client->addScope(Drive::DRIVE_FILE);
            $client->setAccessType('offline');
    
            $service = new Drive($client);
            $adapter = new GoogleDriveAdapter($service, $config['folder_id']);
    
            return new Filesystem($adapter);
        });
    
    }
}
