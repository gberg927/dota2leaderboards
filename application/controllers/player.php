<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Player extends CI_Controller {
    
    public function index() {
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
        $this->db->order_by('history.rank', 'ASC'); 
        $query = $this->db->get();
        
        $data['players'] = $query->result();
        $data['lastUpdate'] = $lastUpdate;
        $data['nextUpdate'] = $nextUpdate;
        
        $this->load->view('includes/header');
        $this->load->view('players/players', $data);
        $this->load->view('includes/footer');
    }
    
    public function id($playerID) {
        if (isset($playerID)) {
            //Get latestUpdate Date
            $this->db->select('lastUpdate');
            $query = $this->db->get('settings', 1);
            $lastUpdate = $query->row()->lastUpdate;
            
            $this->db->select('players.id, history.rank, players.team_tag, players.name, players.country, countries.commonName, players.division, history.solo_mmr');
            $this->db->join('history', 'players.id = history.playerID');
            $this->db->join('countries', 'countries.2LetterCode = players.country');
            $this->db->where('players.id', $playerID);
            $this->db->where('history.date', $lastUpdate);
            $query = $this->db->get('players', 1);
            $data['playerID'] = $playerID;
            $data['rank'] = $query->row()->rank;
            $data['team_tag'] = $query->row()->team_tag;
            $data['name'] = $query->row()->name;
            $data['country'] = $query->row()->country;
            $data['commonName'] = $query->row()->commonName;
            $data['region'] = $query->row()->division;
            $data['solo_mmr'] = $query->row()->solo_mmr;
            
            $this->load->view('includes/header');
            $this->load->view('players/player', $data);
            $this->load->view('includes/footer');
        }
        else {
            index();
        }
    }
    
    public function data($playerID) {
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
    
    public function getRanks($playerID) {
        if (isset($playerID)) {
            $this->db->select('date, rank');
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
                $temp['data'] = $rank->rank;
                array_push($playerInfo, $temp);
            }
            
            echo json_encode($playerInfo, JSON_NUMERIC_CHECK);
        }
    }
    
    public function getSolo_mmrs($playerID) {
        if (isset($playerID)) {
            $this->db->select('date, solo_mmr');
            $this->db->join('players', 'history.playerID = players.id');
            $this->db->where('players.id', $playerID);
            $this->db->order_by('date', 'asc'); 
            $query = $this->db->get('history');
            $data['ranks'] = $query->result();
            $playerInfo = array();

            foreach ($query->result() as $solo_mmr)
            {
                $date = date_create($solo_mmr->date);
                $epoch = date_format($date, 'd-m-Y');
                
                $temp = array();
                $temp['date'] = $epoch;
                $temp['data'] = $solo_mmr->rank;
                array_push($playerInfo, $temp);
            }
            
            echo json_encode($playerInfo, JSON_NUMERIC_CHECK);
        }
    }
}