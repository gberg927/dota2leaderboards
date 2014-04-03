<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaderboards extends CI_Controller {
    
    public function index()
    {
        $this->displayRegion('americas');
    }
    
    public function americas()
    {
        $this->displayRegion('americas');
    }
    
    public function europe()
    {
        $this->displayRegion('europe');
    }
    
    public function se_asia()
    {
        $this->displayRegion('se_asia');
    }
    
    public function china()
    {
        $this->displayRegion('china');
    }
    
    private function displayRegion($region)
    {        
        $this->db->select('history.rank, players.team_tag, players.name, players.country, history.solo_mmr');
        $this->db->from('players');
        $this->db->join('history', 'history.playerID = players.id');
        $this->db->where('players.division', $region);
        $this->db->where('history.date', date('Y-m-d'));
        $query = $this->db->get();
        
        $data['region'] = $region;
        $data['players'] = $query->result();
        $this->load->view('home2', $data);
    }
}