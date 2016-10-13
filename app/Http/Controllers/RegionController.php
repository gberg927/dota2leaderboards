<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Player as Player;
use App\Ranking as Ranking;
use App\Region as Region;
use App\Team as Team;
use App\Date as Date;

class RegionController extends Controller
{
	public function index() {
		return $this->displayRegion('americas');
	}

    public function displayRegion($region_name) {
    	$date_posted = Date::where('setting_name', '=', 'DATE_POSTED')->first();

	    $region = Region::where('name', '=', $region_name)->first();

	    $rankings = Ranking::join('players', 'rankings.player_id', '=', 'players.id')->where('players.region_id', '=', $region->id)->where('rankings.rank_date', '=', $date_posted->value)->orderBy('rankings.rank', 'ASC')->get();

	    $rankDate = Date::find(1);

	    $data = array(
	    	'rankings' => $rankings,
	        'region' => $region,
	        'rankDate' => $rankDate
	    	);
	    return view('layouts.region', $data);
    }
}
