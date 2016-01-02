<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Starter extends CI_Model {


	public function contact_us($data)
	{
		$this->db->set('date_on', 'NOW()', FALSE);
		$this->db->set('ip_address', real_ip());
		$this->db->insert('contact',$data);
		return $this->db->insert_id();
	}


	public function getCountry()
	{
		return $this->db->query("select * from countries")->result();
	}	


	

	
}
