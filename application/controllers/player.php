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
            $this->db->order_by('date', 'desc'); 
            $query = $this->db->get('history');
            $data['ranks'] = $query->result();
            
            $this->load->view('header');
            $this->load->view('player', $data);
            $this->load->view('footer');
        }
        else {
            index();
        }
    }
}