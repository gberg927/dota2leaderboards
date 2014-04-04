<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scraper extends CI_Controller {
    
    public function index() {
        header('Content-type: application/json');
        $this->getRegion('americas');
        $this->getRegion('europe');
        $this->getRegion('se_asia');
        $this->getRegion('china');
    }
    
    private function getRegion($region) {
        $requestURL = 'www.dota2.com/webapi/ILeaderboard/GetDivisionLeaderboard/v0001?division=' . $region;
        $leaderboards = (json_decode($this->file_get_contents_curl($requestURL)));
        $time_posted = date("Y-m-d H:i:s", $leaderboards->time_posted);
        $next_scheduled_post_time = date("Y-m-d H:i:s", $leaderboards->next_scheduled_post_time);
        
        $timeData = array(
            'lastUpdate' => $time_posted,
            'nextUpdate' => $next_scheduled_post_time
        );
        $this->db->update('settings', $timeData); 
        
        foreach($leaderboards->leaderboard as $player) {
            $data = array(
                'name' => $player->name,
                'team_tag' => (isset($player->team_tag) ? $player->team_tag : NULL),
                'division' => $region,
                'country' => (isset($player->country) ? $player->country : NULL)
            );
            $this->savePlayer($data, $player->solo_mmr, $player->rank, $time_posted);       
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
    
    public function savePlayer($data, $solo_mmr, $rank, $time_posted) {
        $playerID = -1;        
        $this->db->select('id');
        $this->db->where('name', $data['name']);
        $query = $this->db->get('players', 1);
        if ($query->num_rows() > 0) {
            $playerID = $query->row()->id;
            $this->db->where('id', $playerID);
            $this->db->update('players', $data); 
        }
        else {
            $this->db->insert('players', $data);
            $playerID = $this->db->insert_id();
        }
        
        $dataHistory = array(
            'playerID' => $playerID,
            'date' => $time_posted,
            'rank' => $rank, 
            'solo_mmr' => $solo_mmr
        );
        $this->db->insert('history', $dataHistory);
    }
}