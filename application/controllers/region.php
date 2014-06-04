<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Region extends CI_Controller {
    
    public function index() {
        $this->load->view('includes/header');
        $this->load->view('regions/regions');
        $this->load->view('includes/footer');
    }
    
    public function americas() {
        $this->displayRegion('americas');
    }
    
    public function europe() {
        $this->displayRegion('europe');
    }
    
    public function se_asia() {
        $this->displayRegion('se_asia');
    }
    
    public function china(){
        $this->displayRegion('china');
    }
    
    private function displayRegion($region) {
        $data['region'] = $region;
        
        //Get latestUpdate Date
        $this->db->select('lastUpdate, nextUpdate');
        $query = $this->db->get('settings', 1);
        $lastUpdate = $query->row()->lastUpdate;
        $nextUpdate = $query->row()->nextUpdate;
        
        $this->db->select('players.id, history.rank, players.team_tag, players.name, players.country, countries.commonName, history.solo_mmr');
        $this->db->from('players');
        $this->db->join('history', 'history.playerID = players.id');
        $this->db->join('countries', 'countries.2LetterCode = players.country');
        $this->db->where('players.division', $region);
        $this->db->where('history.date', $lastUpdate);
        $this->db->order_by('history.rank', 'ASC'); 
        $query = $this->db->get();
        
        $data['region'] = $region;
        $data['players'] = $query->result();
        $data['lastUpdate'] = $lastUpdate;
        $data['nextUpdate'] = $nextUpdate;
        
        $this->load->view('includes/header');
        $this->load->view('regions/region', $data);
        $this->load->view('includes/footer');
    }
}