<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;

use App\Http\Requests;

use App\Player as Player;
use App\Ranking as Ranking;
use App\Region as Region;
use App\Team as Team;
use App\Date as Date;

class ScraperController extends Controller
{
	public function scrapeData() {
		$regions = Region::all();
		foreach ($regions as $region) {
			echo '<h3>Scraping ' . $region->description . ' region.</h3>';
			$this->getRegion($region);
			echo '<hr />';
		}
	}

	private function getRegion($region){
		$requestURL = 'www.dota2.com/webapi/ILeaderboard/GetDivisionLeaderboard/v0001?division=' . $region->name;
        $leaderboards = json_decode($this->file_get_contents_curl($requestURL));

        //Get time information
        $date_posted = date("Y-m-d", $leaderboards->time_posted);
        $time_posted = date("Y-m-d H:i:s", $leaderboards->time_posted);
        $next_scheduled_post_time = date("Y-m-d H:i:s", $leaderboards->next_scheduled_post_time);
        $date = Date::where('setting_name', '=', 'DATE_POSTED')->first();
        if ($date == null){
            $date = new Date();
            $date->setting_name = 'DATE_POSTED';
        }
        $date->value = $date_posted;
        $date->save();

        $date = Date::where('setting_name', '=', 'TIME_POSTED')->first();
        if ($date == null){
            $date = new Date();
            $date->setting_name = 'TIME_POSTED';
        }
        $date->value = $time_posted;
        $date->save();

        $date = Date::where('setting_name', '=', 'NEXT_SCHEDULED')->first();
        if ($date == null){
            $date = new Date();
            $date->setting_name = 'NEXT_SCHEDULED';
        }
        $date->value = $next_scheduled_post_time;
        $date->save();

 		//Parse Rankings
        foreach($leaderboards->leaderboard as $leaderboard) {
        	
        	if (isset($leaderboard->team_id)){
				$team = Team::find($leaderboard->team_id);
    			if ($team == null){
    				$team = new Team();
        			$team->id = $leaderboard->team_id;
        			$team->name = isset($leaderboard->team_tag) ? $leaderboard->team_tag : NULL;
	        		$team->save();
	        		echo '<h5>New team detected</h5>';
	        		echo '<p>' . json_encode($team, JSON_PRETTY_PRINT) . '</p>';
    			}        		
        	}

        	$player = Player::where('name', '=', $leaderboard->name)->where('region_id', '=', $region->id)->first();
        	if ($player == null){
        		$player = new Player();
        		$player->name = $leaderboard->name;
        		$player->region_id = $region->id;
        		$player->team_id = isset($leaderboard->team_id) ? $leaderboard->team_id : NULL;
				$player->country = isset($leaderboard->country) ? $leaderboard->country : NULL;
				$player->save();
				echo '<h5>New player detected</h5>';
	    		echo '<p>' . json_encode($player, JSON_PRETTY_PRINT) . '</p>';
			}
			

			$ranking = Ranking::where('player_id', '=', $player->id)->where('rank_date', '=', $date_posted)->first();
			if ($ranking == null){
	        	$ranking = new Ranking();
	        	$ranking->player_id = $player->id;
	        	$ranking->rank = $leaderboard->rank;
        		$ranking->solo_mmr = $leaderboard->solo_mmr;
        		$ranking->rank_date = $date_posted;
        		$ranking->save();
        		echo '<h5>New ranking detected</h5>';
    			echo '<p>' . json_encode($ranking, JSON_PRETTY_PRINT) . '</p>';
	        }
        }

	}

	private function file_get_contents_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}