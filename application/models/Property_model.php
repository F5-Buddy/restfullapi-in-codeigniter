<?php
class Property_model extends CI_Model{

	public function get_all_country(){	
		$this->db->select('*');		
		$this->db->from('countries');
		$this->db->where('countries.location_type', 0 );		
		$query=$this->db->get();
		return $query->result_array();
	}


	public function do_get_all_state($id){
		$this->db->select('*');
		$this->db->from('countries');
		$this->db->where('parent_id',$id);
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function do_save_property($create_data){
		$res=$this->db->insert('property',$create_data);
		if($res){
		 	$id = $this->db->insert_id();
         	return $id;
        }
	}

	public function insert_property_image($property_id,$image_nm){
		$data=array('property_image'=>serialize($image_nm));
		$this->db->where('property_id',$property_id);	
		$this->db->update('property',$data);		
	}

	public function get_total_my_property($user_id){
		$this->db->select('*');		
		$this->db->from('property');
		$this->db->where('property.property_uploader_id',$user_id);
		$this->db->where('property.property_status', 1 );
    	$query = $this->db->get();        
        return $query->num_rows();
	}
	
	public function get_my_property($limit,$start,$user_id){	
		$this->db->select('*');		
		$this->db->from('property');
		$this->db->where('property.property_uploader_id',$user_id);
		$this->db->where('property.property_status', 1 );
		//$this->db->join('country', 'country.location_id=real_estate.country ','left');
		$this->db->order_by('property.property_created_date','desc');
		$this->db->limit($limit,$start);
		$query=$this->db->get();
		return $query->result_array();
	}

	public function do_delete_property($property_id){
		$data=array('property_status'=>0);
		$this->db->where('property_id',$property_id);	
		$this->db->update('property',$data);
	}

	public function get_property_detail($property_id,$user_id){
		$this->db->select('property.*,countries.country_name as country_name');		
		$this->db->from('property');
		$this->db->where('property.property_id', $property_id );
		$this->db->where('property.property_uploader_id',$user_id);
		$this->db->where('property.property_status', 1 );
		$this->db->join('countries', 'countries.id=property.country_code ','left');	
		$query=$this->db->get();
		$rs=$query->result_array();

		$this->db->select('countries.country_name as state_name');		
		$this->db->from('countries');
		$this->db->join('property', 'property.state=countries.id ','left');
		$this->db->where('property.property_uploader_id',$user_id);
		$this->db->where('property.property_id', $property_id );
		$this->db->where('property.property_status', 1 );			
		$query=$this->db->get();
		$res=$query->result_array();
		$result=array_merge($rs,$res);
		return $result;
	}

	public function do_update_property($update_data,$property_id){
		$data=array('property_category'=>$update_data['property_category'],'property_type'=>$update_data['property_type'],'price'=>$update_data['price'],'no_of_bedroom'=>$update_data['no_of_bedroom'],'no_of_bathroom'=>$update_data['no_of_bathroom'],'buildup_area'=>$update_data['buildup_area'],'area'=>$update_data['area'],'garage'=>$update_data['garage'],'address'=>$update_data['address'],'contact_no'=>$update_data['contact_no'],'city'=>$update_data['city'],'state'=>$update_data['state'],'country_code'=>$update_data['country_code'],'posted_by'=>$update_data['posted_by'],'description'=>$update_data['description']);
		//echo '<pre>';print_r($data);die;
		$this->db->where('property_id',$property_id);	
		$res=$this->db->update('property',$data);
		return $res;
	}

	public function get_total_property_listing(){
		$this->db->select('*');		
		$this->db->from('property');
		//$this->db->where('property.property_uploader_id',$user_id);
		$this->db->where('property.approvel_status', 1 );
		$this->db->where('property.property_status', 1 );
    	$query = $this->db->get();        
        return $query->num_rows();
	}
	
	public function get_property_listing($limit,$start){	
		$this->db->select('property.*,countries.country_name as country_name');		
		$this->db->from('property');
		$this->db->where('property.approvel_status', 1 );
		$this->db->where('property.property_status', 1 );
		$this->db->join('countries', 'countries.id=property.country_code ','left');
		$this->db->order_by('property.property_created_date','desc');
		$this->db->limit($limit,$start);
		$query=$this->db->get();

		$mydata = $query->result_array();
		$myimage = "";
		$all_rs=array();
		
		foreach ($mydata as $k=>$value) {
			//$myimage['myimage'] = unserialize($value['property_image']);
			//$myimage['test'] = $value;			
			$img=unserialize($value['property_image']);
			$value['all_img']=$img;
			$all_rs[]=$value;
		}
		return $all_rs;
	}

	public function get_quick_view_detail($property_id){
		$this->db->select('property.*,countries.country_name as country_name');		
		$this->db->from('property');
		$this->db->where('property.property_id', $property_id );
		//$this->db->where('property.property_uploader_id',$user_id);
		$this->db->where('property.approvel_status', 1 );
		$this->db->where('property.property_status', 1 );
		$this->db->join('countries', 'countries.id=property.country_code ','left');	
		$query=$this->db->get();
		$rs=$query->result_array();

		$this->db->select('countries.country_name as state_name');		
		$this->db->from('countries');
		$this->db->join('property', 'property.state=countries.id ','left');
		$this->db->where('property.property_id', $property_id );
		$this->db->where('property.approvel_status', 1 );
		$this->db->where('property.property_status', 1 );			
		$query=$this->db->get();
		$res=$query->result_array();
		$result=array_merge($rs,$res);
		return $result;
	}
	
}