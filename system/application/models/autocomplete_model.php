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
    /**
    * get suppleir by code, retrieve the last supplier
    */
    function get_last_sup($key)
    {
        $this->db->like('sup_code',$key,'after')->order_by('sup_code','desc');
        return $this->db->get('supplier');
    }
    /**
    * Retrieve data item
    */
	function get_item($key="")
	{
		$query = 'select * from item left join supplier on item.sup_code=supplier.sup_code where item_code like "'.$key.'%"';
		return $this->db->query($query);
	}
    /**
    * Retrieve data item untuk mutasi keluar, harus yang stoknya > 0
    */
	function get_item_for_mutasi_keluar($key="")
	{
		$query = 'select * from item left join supplier on item.sup_code=supplier.sup_code where item_code like "'.$key.'%" and item_qty_stock > 0';
		return $this->db->query($query);
	}
    /**
    * Retrieve data item untuk retur, harga jual > 0
    */
    function get_item_for_retur($key="")
	{
		$query = 'select * from item left join supplier on item.sup_code=supplier.sup_code where item_code like "'.$key.'%" and item_hj > 0';
		return $this->db->query($query);
	}
    /**
	*Retrieve data shop for autocomplete
	*/
	function get_shop($key="")
	{
		$this->db->like('shop_name',$key,'after')->order_by('shop_name','asc');		    
		$query = $this->db->get('shop');        
		return $query;
	}
}
//End of file autocomplete_model.php