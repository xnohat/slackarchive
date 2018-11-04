<?php

namespace App;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserChannel extends Model
{
	use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['userid', 'channelid'];
}
