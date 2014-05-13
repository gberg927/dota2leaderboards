<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function index() {
        $data['americas'] = $this->getFirst('americas');
        $data['europe'] = $this->getFirst('europe');
        $data['se_asia'] = $this->getFirst('se_asia');
        $data['china'] = $this->getFirst('china');
        
        $this->load->view('includes/header');
        $this->load->view('home', $data);
        $this->load->view('includes/footer');
    }
    
    private function getFirst($region) {        
        //Get latestUpdate Date
        $this->db->select('lastUpdate');
        $query = $this->db->get('settings', 1);
        $lastUpdate = $query->row()->lastUpdate;
        
        $this->db->select('players.id, history.rank, players.team_tag, players.name, players.country, players.division, history.solo_mmr');
        $this->db->join('history', 'history.playerID = players.id');
        $this->db->where('players.division', $region);
        $this->db->where('history.date', $lastUpdate);
        $this->db->order_by('history.rank', 'ASC'); 
        $query = $this->db->get('players', 1);
        
        $data['playerID'] = $query->row()->id;
        $data['rank'] = $query->row()->rank;
        $data['team_tag'] = $query->row()->team_tag;
        $data['name'] = $query->row()->name;
        $data['country'] = $query->row()->country;
        $data['region'] = $query->row()->division;
        $data['solo_mmr'] = $query->row()->solo_mmr;
        
        return $data;
    }
}