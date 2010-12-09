<?php
/**
*Model Item
*Handling database transaction with database table 'Item'
*/
class Item extends Model {
	function Item()
	{
		parent::Model();
	}
	/**
	*retrieving data item with parameter
	*/
	function get_item_by_cat($cat_code)
	{
        $this->db->order_by('item_code','desc');
		return $this->db->get_where('item',array('cat_code'=>$cat_code));
	}
    /**
    *insert new item
    *@param : $data -> array item data
    */
    function add_item($data)
    {
        return $this->db->insert('item',$data);
    }
}
//end of file gudang_model.php