<?php
class User_model extends CI_Model{	

	public function do_check_user_email($email){
		$this->db->select('email');
		$this->db->from('users');
		$this->db->where('email',$email);
		$query=$this->db->get();
		return $query->result_array();
	}
}