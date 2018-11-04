<?php

namespace App;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Channels extends Model
{
    protected $fillable = ['name', 'channelid'];

    public function getChannelById($id){
        return Cache::remember('app_get_channel_by_id_'.$id, config('slackarchive.query_cache.timeout_long'), function()use($id){
            $channel = Channels::where('channelid', strtoupper($id))->first();
            return $channel;
        });
    }

    public function getChannelByName($name){
        return Cache::remember('app_get_channel_by_name_'.$name, config('slackarchive.query_cache.timeout_long'), function()use($name){
            $channel = Channels::where('name', strtolower($name))->first();
            return $channel;
        });
    }

}
