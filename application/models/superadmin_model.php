<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class superadmin_model extends CI_Model {

	public function getCountry()
	{
		return $this->db->query("select * from countries where parent_id=0 AND location_type=0 AND is_visible=0")->result();
	}

	public function getalladmin()
	{
		return $users= $this->db->query("SELECT user.*,ug.user_id,ug.group_id FROM `users` as user
inner join users_groups as ug on ug.user_id = user.id
where ug.group_id = 2")->result();
	}

	public function checkadmin($id)
	{
		return $this->db->query("select * from users where id = $id")->num_rows();
	}

	public function checkproperty($id)
	{
		return $this->db->query("select * from property where property_id=$id")->num_rows();
	}


	public function getadmin($id)
	{
		return $this->db->query("select * from users where id = $id")->result();
	}


	public function getalluser($perpage,$limit)
	{
		return $users= $this->db->query("SELECT user.*,ug.user_id,ug.group_id,count(pt.property_uploader_id)as cnt FROM `users` as user
inner join users_groups as ug on ug.user_id = user.id
left join property as pt  on pt.property_uploader_id = user.id
where ug.group_id = 3 group by pt.property_uploader_id LIMIT $limit,$perpage")->result();
	}

	public function getallusercount()
	{
		return $users= $this->db->query("SELECT user.*,ug.user_id,ug.group_id FROM `users` as user
inner join users_groups as ug on ug.user_id = user.id
where ug.group_id = 3")->num_rows();
	}

	public function getallproperty($perpage,$limit,$property_u_id)
	{
		if(isset($property_u_id) && $property_u_id>0)
		{
			return $this->db->query("select * from property where property_uploader_id=$property_u_id AND property_status=1 order by property_created_date asc limit $limit,$perpage")->result();
		}else{
			return $this->db->query("select * from property where property_status=1 order by property_created_date desc limit $limit,$perpage")->result();
		}
	}

	public function getallpropertycount($id)
	{
		if(isset($id) && $id>0)
		{
			return $this->db->query("select * from property where property_uploader_id=$id AND property_status=1 order by property_created_date asc")->num_rows();
		}else{
			return $this->db->query("select * from property where property_status=1 order by property_created_date asc")->num_rows();
		}
	}

	public function updateapprovestatus($id,$status)
	{
		if($status==0)
		{
			$this->db->query("update property set approvel_status=1 where property_id=$id");
		}else{
			$this->db->query("update property set approvel_status=0 where property_id=$id");
		}
		
	}

    
	public function getproperty($id)
	{
	
		return $this->db->query("select property.*,users.email,CONCAT(first_name,' ',last_name)as full_name from property inner join users on users.id = property.property_uploader_id where property.property_id = $id")->result();
	}






	

	
}
