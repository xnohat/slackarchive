<?php

namespace App;

use Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function messages(){
        return $this->hasMany('App\Messages','user','userid');
    }

    public function getUserById($id){
        return Cache::remember('app_get_user_by_id_'.$id, config('slackarchive.query_cache.timeout_long'), function()use($id){
            $user = User::where('userid', strtoupper($id))->first();
            return $user;
        });
    }

    public function getUserByName($name){
        return Cache::remember('app_get_user_by_name_'.$name, config('slackarchive.query_cache.timeout_long'), function()use($name){
            $user = User::where('name', strtolower($name))->first();
            return $user;
        });
    }

}
