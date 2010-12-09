<?php
/**
*Model Category
*Handling database transaction with database table 'Category'
*/
class Category extends Model {
	function Item()
	{
		parent::Model();
	}
	/**
	*retrieving data category item
	*/
	function get_category($cat_code)
	{
		return $this->db->get_where('category',array('cat_code'=>$cat_code));
	}
}
//end of file gudang_model.php