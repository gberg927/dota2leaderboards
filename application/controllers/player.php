<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Player extends CI_Controller {
    
    public function index() {
        
    }
    
    public function id($playerID) {
        if (isset($playerID)) {
            $this->db->select('name, division, country');
            $this->db->where('id', $playerID);
            $query = $this->db->get('players', 1);
            $data['name'] = $query->row()->name;
            $data['region'] = $query->row()->division;
            $data['country'] = $query->row()->country;
            
            $this->db->select('date, rank, solo_mmr');
            $this->db->join('players', 'history.playerID = players.id');
            $this->db->where('players.id', $playerID);
            $this->db->order_by('date', 'asc'); 
            $query = $this->db->get('history');
            $data['ranks'] = $query->result();
            $rankArray = array();
            $solo_mmrArray = array();
            
            foreach ($query->result() as $rank)
            {
                $date = date_create($rank->date);
                $epoch = date_format($date, 'U');

                $currentRank = array('x' => $epoch, 'y' => $rank->rank);
                $currentSolo_mmr = array('x' => $epoch, 'y' => $rank->solo_mmr);
                array_push($rankArray, $currentRank);
                array_push($solo_mmrArray, $currentSolo_mmr);
            }
            
            $data['rankArray'] = json_encode($rankArray, JSON_NUMERIC_CHECK);
            $data['solo_mmrArray'] = json_encode($solo_mmrArray, JSON_NUMERIC_CHECK);
            
            $this->load->view('header');
            $this->load->view('player', $data);
            $this->load->view('footer');
        }
        else {
            index();
        }
    }
}