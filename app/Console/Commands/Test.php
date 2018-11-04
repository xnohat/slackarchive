<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use ReactEventLoopFactory;
use SlackRealTimeClient;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing Command';

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
        $loop = ReactEventLoopFactory::create();

        $client = new SlackRealTimeClient($loop);
        $client->setToken('xoxb-242344428404-Q2pDFA0PXyco2GLXMSOaZGwh');

        // disconnect after first message
        $client->on('message', function ($data) use ($client) {
            //echo "Someone typed a message: ".$data['text']."\n";
            print_r($data);
            //$client->disconnect();
        });

        $client->connect()->then(function () {
            echo "Connected!\n";
        });

        $loop->run();
    }
}
