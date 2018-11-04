<?php

namespace App\Http\Controllers;

use Log;
use Session;

use Facades\App\Channels;
use Facades\App\Messages;
use Facades\App\User;
use Facades\App\Classes\SlackClient;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Set Viewing Channel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setViewingChannel(Request $request)
    {
        if($request->input('secret') != config('slackarchive.secret_key')){
            return response('403 Forbidden', 403);
            die();
        }
        Session::start();
        session()->put('viewingchannel', $request->input('channel_id'));

        $response = [
                'text' => '*Click this link to view history:*',
                'mrkdwn' => true,
                'attachments' => [
                    ['color' => '#ff0000','text' => 'http://'.config('slackarchive.full_domain').'/viewhistory?sid='.Session::getId()],
                ],
            ];

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if(request()->query('sid') == ''){
            return response('403 Forbidden', 403);
            die();
        }
        
        Session::setId(request()->query('sid'));
        Session::start();
        
        $viewingchannel = session()->get('viewingchannel');

        $sessionid = Session::getId();

        $c = Channels::getChannelById($viewingchannel);
        $m = Messages::getMessages($viewingchannel);

        if(!isset($c) or !isset($m)){
            return response('Your Session is EXPIRED, Please run /history command again in Slack Channel', 404);
        }

        return view('viewhistory',['type' => 'feed', 'channel' => $c->name, 'messages' => $m, 'sessionid' => $sessionid]);
    }

    /**
     * Search.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        Session::setId(request()->query('sid'));
        Session::start();
        
        $viewingchannel = session()->get('viewingchannel');

        $sessionid = Session::getId();

        $searchresults = Messages::ESsearchMessages(urldecode($request->input('query')), $viewingchannel);

        $c = Channels::getChannelById($viewingchannel);
        return view('viewhistory',['type' => 'search', 'channel' => $c->name,'query' => $request->input('query'),'messages' => $searchresults, 'sessionid' => $sessionid]);

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
