<?php

namespace App\Providers;

use Native\Laravel\Facades\Window;
use Native\Laravel\Contracts\ProvidesPhpIni;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Config;
use Illuminate\Support\Facades\Schema;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Window::open()
            ->maximized();

            $databasePath = database_path('nativephp.sqlite');
            if (!File::exists($databasePath)) {
                File::put($databasePath, ''); 
                Artisan::call('migrate', ['--force' => true]);
                // Artisan::call('db:seed', ['--force' => true]);
            }
        
        

    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}
