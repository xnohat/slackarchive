<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Messages;
use Log;

class ESReindexAllMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:reindexallmessages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex all Messages';

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
        $this->info("Reindexing All Posts...");
        Messages::reindex();
        $this->info("Reindexed All Posts !");
    }
}
