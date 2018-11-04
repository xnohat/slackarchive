<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Classes\SlackClient;
use Facades\App\User;

use App\Channels;
use App\Messages;
use App\UserChannel;

use SlackApi;
use SlackChannel;
use SlackGroup;
use SlackUser;
use SlackChat;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //$SlackClient = new SlackClient();
        //$SlackClient->updateUsers();
        //$SlackClient->updateChannels();
        //$SlackClient->sendMessage('C7558T26A', 'Hi Phuc');
        //$SlackClient->updateMessages();
        //echo slack_file_downloader('https://files.slack.com/files-pri/T386ELDFF-F7A9T8V0F/download/bugfixed.jpg');

        $channels = SlackGroup::lists(0);
        var_dump($channels);
        //dd($channels);

        /* $channelmessages = SlackGroup::history('GDW80NBM5', 100);

        $message = $channelmessages->messages;
    
        var_dump($message); */

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
