<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Messages;
use Log;

class ESAddAllMessageToIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:addallmessagetoindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add All Messages to ES Index';

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
        $this->info("Adding Messages to ES Index");
        Messages::addAllToIndex();
        $this->info("Added All Messages to ES Index");
    }
}
