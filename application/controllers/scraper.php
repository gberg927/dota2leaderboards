<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scraper extends CI_Controller {
    
    public function index()
    {
    }
    
    public function americas()
    {
        $this->getRegion('americas');
    }
    
    public function europe()
    {
        $this->getRegion('europe');
    }
    
    public function se_asia()
    {
        $this->getRegion('se_asia');
    }
    
    public function china()
    {
        $this->getRegion('china');
    }
    
    private function getRegion($region)
    {
        $requestURL = 'www.dota2.com/webapi/ILeaderboard/GetDivisionLeaderboard/v0001?division=' . $region;
        header('Content-type: application/json');
        $leaderboards = (json_decode($this->file_get_contents_curl($requestURL)));
        foreach($leaderboards->leaderboard as $player) {
            $data = array(
                'date' => now(),
                'name' => $player->name,
                'rank' => $player->rank,
                'solo_mmr' => $player->solo_mmr,
                'country' => (isset($player->country) ? $player->country : '')
            );
            $this->db->insert($region, $data);           
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