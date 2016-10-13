<?php

namespace App;

use Sun\Country as Country;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
	public function team(){
		return $this->belongsTo('App\Team');
	}

	public function region(){
		return $this->hasOne('App\Region');
	}

	public function rankings(){
		return $this->hasMany('App\Ranking')->orderBy('rank_date', 'desc');
	}
}
