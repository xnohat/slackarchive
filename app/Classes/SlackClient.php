<?php

namespace App\Classes;

use App\User;
use App\Channels;
use App\Messages;
use App\UserChannel;

use SlackApi;
use SlackChannel;
use SlackGroup;
use SlackUser;
use SlackChat;

class SlackClient {

	public static $token;
	
	public function __construct(){
		self::$token = config('services.slack.token');
	}

	public function updateUsers(){
		$listusers = SlackUser::lists();
		$members = $listusers->members;

		foreach($members as $member){
			//echo $member->name."\n";
			//echo $member->id."\n";
			//echo $member->profile->image_72."\n";

			//Insert if item does not exist or Update if Item exists.
			$user = User::firstOrNew(['userid' => $member->id]); //RELACE ON CONFLICT
			$user->name = $member->name;
			$user->userid = $member->id;
			$user->avatar = $member->profile->image_72;
			$user->deleted = $member->deleted;
			$user->save();

		}
	}

	public function updateChannels(){
		$listchannels = SlackChannel::lists(0);
		$channels = $listchannels->channels;

		foreach ($channels as $channel) {
			/*echo $channel->name."\n";
			echo $channel->id."\n";
			echo $channel->is_archived."\n";
			echo "\n----\n";*/

			//Insert if item does not exist or Update if Item exists.
			$c = Channels::firstOrNew(['channelid' => $channel->id]); //RELACE ON CONFLICT
			$c->name = $channel->name;
			$c->channelid = $channel->id;
			$c->is_archived = $channel->is_archived;
			$c->is_group = false;
			$c->save();

		}

		$listgroups = SlackGroup::lists(0);
		$groups = $listgroups->groups;

		foreach ($groups as $group) {
			/*echo $group->name."\n";
			echo $group->id."\n";
			echo $group->is_archived."\n";
			echo "\n----\n";*/

			//Insert if item does not exist or Update if Item exists.
			$g = Channels::firstOrNew(['channelid' => $group->id]); //RELACE ON CONFLICT
			$g->name = $group->name;
			$g->channelid = $group->id;
			$g->is_archived = $group->is_archived;
			$g->is_group = true;
			$g->save();

		}

	}

	public function updateChannelUserList($channelid){

	}

	public function sendMessage($channel, $message){
		$response = SlackChat::message($channel, $message);
		//var_dump($response);
	}

	public function updateMessages($limit = 300){

		//Update Channel List First
		$this->updateChannels();
		sleep(1);

		$Channels = new Channels;
		$channellist = $Channels->where('is_archived', 0)->get();

		foreach ($channellist as $c) {
		
			echo $c->channelid.":".$c->name."\n";

			$channel = $c->channelid;
		
			if($c->is_group == false){
				$channelmessages = SlackChannel::history($channel, $limit);
			}else{
				$channelmessages = SlackGroup::history($channel, $limit);
			}
			sleep(1);

			//Check channel is empty ?
			if(!isset($channelmessages->messages)) continue;

			foreach ($channelmessages->messages as $message) {
				//echo $message->text;

				//Check Message exists ?
				if(!isset($message)) continue;
				//Check Message Sender is Bot ?
				if(isset($message->bot_id)) continue;
				//Check Message have Sender ?
				if(!isset($message->user)) continue;
				//Check Message have Text ?
				if(!isset($message->text)) continue;

				//Check Message Sender is in DB ?
				$User = new User;
				$u = $User->getUserById($message->user);
				if(!isset($u)){
					$this->updateUsers();
					sleep(1);
				}

				//Insert new message to DB
                $m = Messages::firstOrNew(['user' => $message->user, 'ts' => convert_timestamp($message->ts)]);
                $m->message = $message->text;
                $m->user = $message->user;
                $m->channel = $channel;
                $m->ts = convert_timestamp($message->ts);
                $m->save();

				if ($m->wasRecentlyCreated) {
				    // "firstOrCreate" didn't find the record in the DB, so it created it.
					
					if(isset($message->files)){						
						//$file_url = slack_file_downloader($message->files[0]->url_private);

						$m->fileid = $message->files[0]->id;
						$m->filename = $message->files[0]->name;
						$m->filetitle = $message->files[0]->title;
						$m->filetype = $message->files[0]->filetype;
						$m->file_slack_url = $message->files[0]->url_private;
						//$m->file_url = $file_url;
					}

					$m->save();
				    $m->addToIndex(); //add to ES Index
				
				} else {
				    // "firstOrCreate" found the record in the DB, do Update and fetched it.
				}

			}

			sleep(1);
		}
		
	}

}

?>