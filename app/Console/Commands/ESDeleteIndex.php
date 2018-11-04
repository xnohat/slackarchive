<?php

namespace App\Console\Commands;

use App\Messages;
use Log;

use Illuminate\Console\Command;

class ESDeleteIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:deleteindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all data in Index';

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
        $this->info("Deleting Index...");
        Messages::deleteIndex();
        $this->info("Deleted Index !");
        $this->info("Creating Index");
        Messages::createIndex();
        $this->info("Created Index !");
        
    }
}
