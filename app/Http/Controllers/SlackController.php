<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Messages;
use App\User;
use App\Classes\SlackClient;
use Log;

class SlackController extends Controller
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
     * Doing Slack Find.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function slack(Request $request)
    {

        if($request->input('secret') != config('slackarchive.secret_key')){
            return response('403 Forbidden', 403);
            die();
        }

        //Log::debug($request->all());

        //COMMAND: find
        if($request->input('cmd') == 'find'){


            $Users = new User;

            $Messages = new Messages;
            $searchresults = $Messages->ESsearchMessages($request->input('text'), $request->input('channel_id'));

            $attachments = array();
            foreach ($searchresults as $sr) {
                //if($sr->channel == $request->input('channel_id')){ //only accept results from channel user stay in
                    $sender = $Users->getUserById($sr->user)->name;
                    $attachments[] = ['color' => '#FFA500','mrkdwn_in' => ['text','title'],'text' => "[".$sr->ts."] ".$sender.": ".$sr->message];
                //}
            }


            $response = [
                'text' => '*Search Result of `'.$request->input('text').'`:*',
                'mrkdwn' => true,
                'attachments' => $attachments,
            ];

            /*$response = [
                'text' => '*Search Result:*',
                'mrkdwn' => true,
                'attachments' => [
                    ['color' => '#ff0000','text' => 'Line1'],
                    ['color' => '#ff0000','text' => 'Line2'],
                ],
            ];*/

            //Log::debug(response()->json($response, 200));

            return response()->json($response, 200);
        }
        
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
