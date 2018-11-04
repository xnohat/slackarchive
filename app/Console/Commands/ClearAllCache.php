<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use File;

class ClearAllCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-all-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear All Laravel Caches, one command for clear all type of caches one time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('clear-compiled');
        $this->info('+ Cleaned Compliled Cache');

        Artisan::call('cache:clear');
        $this->info('+ Cleaned Facade Cache');

        Artisan::call('route:clear');
        $this->info('+ Cleaned Route Cache');

        Artisan::call('route:cache');
        $this->info('> Rebuilded Route Cache');

        Artisan::call('view:clear');
        $this->info('+ Cleaned View Cache');

        Artisan::call('config:cache');
        $this->info('+ Cleaned Config Cache');

        File::cleanDirectory(storage_path('app/purifier'));
        $this->info('+ Cleaned HTMLPurifier Cache');

        Artisan::call('optimize');
        $this->info('+ Reoptimized class loader');

        $this->info('[!] Cleaned All Caches !');
    }
}
