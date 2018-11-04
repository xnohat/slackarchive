<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use ReactEventLoopFactory;
use SlackRealTimeClient;

use App\Classes\SlackClient;
use App\User;
use App\Channels;
use App\Messages;
use Log;

class Bot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Slack Archive Bot';

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
    
        while (true) {
     
            $loop = ReactEventLoopFactory::create();

            $client = new SlackRealTimeClient($loop);
            $client->setToken(config('services.slack.token'));

            // disconnect after first message
            $client->on('message', function ($data) use ($client) {
                //echo "Someone typed a message: ".$data['text']."\n";
                //print_r($data);
                //$client->disconnect();
                
                if($data['type'] == 'message'){
                    
                    if(!isset($data['text'])) return;
                    if(isset($data['username']) && $data['username'] == 'bot') return;

                    print_r($data);

                    if(substr($data['channel'], 0, 1) == 'D'){
                        //If it's a DM, treat it as a search query
                        echo "Direct Message: ";
                        print_r($data);
                    }elseif(!isset($data['user'])){
                        $this->error('No valid user. Previous event not saved');
                        Log::debug('No valid user. Previous event not saved');
                    }else{
                        //Insert new message to DB
                        $message = new Messages;
                        $message->message = $data['text'];
                        $message->user = $data['user'];
                        $message->channel = $data['channel'];
                        $message->ts = convert_timestamp($data['ts']);
                        $message->save();

                        $message->addToIndex(); //add to ES Index
                    }


                }//END IF DATA TYPE CHECK
                $client->disconnect();

            });

            $client->connect()->then(function () {
                echo "Connected!\n";
            });

            $loop->run();

        }//END WHILE INF
    }
}
