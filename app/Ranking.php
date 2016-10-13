<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    public function player(){
		return $this->belongsTo('App\Player');
	}
}
