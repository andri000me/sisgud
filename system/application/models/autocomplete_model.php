<?php
/**
*MOdel auto complete for autocomplete controller
*/
class Autocomplete_model extends Model {
	/**
	*Class constructor
	*/
	function Autocomplete_model()
	{
		parent::model();
	}
	/**
	*Retrieve data supplier for autocomplete
	*/
	function get_supplier($key="")
	{
		$this->db->like('sup_name',$key,'after')->order_by('sup_name','asc');		    
		$query = $this->db->get('supplier');        
		return $query;
	}
	function get_item($key="")
	{
		$this->db->like('item_code',$key,'after');
		$this->db->order_by('item_code','desc');
		$query = $this->db->get('item');
		return $query;
	}
}
//End of file autocomplete_model.php