<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Facades\App\Classes\SlackClient;
use Facades\App\User;
use Facades\App\Channels;
use Facades\App\Messages;
use Log;

class BotCrawl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:crawl {limit=300}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Bot Crawler';

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
        SlackClient::updateMessages($this->argument('limit'));
    }
}
