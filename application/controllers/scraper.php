<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scraper extends CI_Controller {
    
    public function index() {
        header('Content-type: application/json');
        $this->getRegion('americas');
        $this->getRegion('europe');
        $this->getRegion('se_asia');
        $this->getRegion('china');
    }
    
    public function dbtest($playerName) {
        $this->db->select('players.id, players.team_tag, players.name');
        $this->db->from('players');
        $query = $this->db->get();
        $players = $query->result();
        $i = 0;
        $max = 10;
 
        $startTime = time();
        echo "<h2>" . $max . "/" . count($players) . "</h3>";
        foreach($players as $player) {
            echo "<h3>" . $player->name . "</h3>";
            if ($i < $max) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.53 Safari/525.19"); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
                curl_setopt($ch, CURLOPT_URL, "http://dotabuff.com/search?q=" . $player->name);
                $data = curl_exec($ch);
                curl_close($ch);
                if (strpos($data, "redirected") > 0) {
                    echo 'found';
                }
                else {
                    echo 'not found';
                }
            }
            $i++;
            echo "<hr>";
        }
        $difference = time() - $startTime;
        echo "<h3>" . $difference . " Second</h3>";
    }
    
    public function dotabuffPopulate() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.53 Safari/525.19"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, "http://dotabuff.com/players/verified");
        $data = curl_exec($ch);
        curl_close($ch);
        
        $verified = array();
        $verifiedPlayer = array();
        
        $dom = new domDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($data);
        libxml_clear_errors();
        $dom->preserveWhiteSpace = false;
        
        $playerRows = $dom->getElementsByTagName("td");
        foreach($playerRows as $row) {
            foreach($row->attributes as $attr) {
                if ($attr->value == "cell-xlarge") {
                    $links = $row->getElementsByTagName("a");
                    $link = $links->item(0);
                    $verifiedPlayer['name'] = $link->nodeValue;
                    $verifiedPlayer['dotabuffID'] = substr($link->getAttribute("href"), 9);
                    array_push($verified, $verifiedPlayer);
                }
            }
        }
        
        $this->db->select('players.id, players.team_tag, players.name');
        $this->db->from('players');
        $query = $this->db->get();
        $players = $query->result();
        
        $count = 0;
        
        foreach($players as $player) {
            foreach($verified as $vPlayer) {
                if ($player->name == $vPlayer['name'] || ($player->team_tag . "." . $player->name) == $vPlayer['name']) {
                    echo "<h3>Player Match</h3><br>";
                    echo $player->name . "/" . $vPlayer['name'];
                    echo "<hr>";
                    $count++;
                }
            }
        }
        echo "<h3>" . $count . "</h3>";
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