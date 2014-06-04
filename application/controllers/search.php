<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {
    
    public function find() {
        $search = $_POST['search'];
        if (isset($search)) {
            //Get latestUpdate Date
            $this->db->select('lastUpdate, nextUpdate');
            $query = $this->db->get('settings', 1);
            $lastUpdate = $query->row()->lastUpdate;
            $nextUpdate = $query->row()->nextUpdate;

            $this->db->select('players.id, history.rank, players.team_tag, players.name, players.country, countries.commonName, players.division, history.solo_mmr');
            $this->db->from('players');
            $this->db->join('history', 'history.playerID = players.id');
            $this->db->join('countries', 'countries.2LetterCode = players.country');
            $this->db->where('history.date', $lastUpdate);
            $where = "(players.name LIKE '%" . $search . "%' OR players.team_tag LIKE '%" . $search . "%' OR players.team_tag+players.name LIKE '%" . $search . "%')";
            $this->db->where($where);
            $this->db->order_by('history.rank', 'ASC'); 
            $query = $this->db->get();
            $data['players'] = $query->result();
            
            //country
            $this->db->select('players.id, history.rank, players.team_tag, players.name, players.country, countries.commonName, players.division, history.solo_mmr');
            $this->db->from('players');
            $this->db->join('history', 'history.playerID = players.id');
            $this->db->join('countries', 'countries.2LetterCode = players.country');
            $this->db->where('history.date', $lastUpdate);
            $this->db->like('countries.commonName', $search);
            $this->db->order_by('history.rank', 'ASC'); 
            $query = $this->db->get();
            $data['country'] = $query->result();
            
            $this->load->view('includes/header');
            $this->load->view('search', $data);
            $this->load->view('includes/footer');
        }
    }
}