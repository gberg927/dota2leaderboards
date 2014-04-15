<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Player extends CI_Controller {
    
    public function index() {
        
    }
    
    public function id($playerID) {
        if (isset($playerID)) {
            $this->db->select('name, division, country');
            $this->db->where('id', $playerID);
            $query = $this->db->get('players', 1);
            $data['playerID'] = $playerID;
            $data['name'] = $query->row()->name;
            $data['region'] = $query->row()->division;
            $data['country'] = $query->row()->country;
            
            $this->load->view('header');
            $this->load->view('player', $data);
            $this->load->view('footer');
        }
        else {
            index();
        }
    }
    
    public function solo_mmr($playerID) {
        if (isset($playerID)) {
            $this->db->select('date, rank, solo_mmr');
            $this->db->join('players', 'history.playerID = players.id');
            $this->db->where('players.id', $playerID);
            $this->db->order_by('date', 'asc'); 
            $query = $this->db->get('history');
            $data['ranks'] = $query->result();
            $playerInfo = array();

            foreach ($query->result() as $rank)
            {
                $date = date_create($rank->date);
                $epoch = date_format($date, 'd-m-Y');
                
                $temp = array();
                $temp['date'] = $epoch;
                $temp['rank'] = $rank->rank;
                $temp['solo_mmr'] = $rank->solo_mmr;
                
                array_push($playerInfo, $temp);
            }
            
            echo json_encode($playerInfo, JSON_NUMERIC_CHECK);
        }
    }
}