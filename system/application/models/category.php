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
    /**
    *search category
    */
    function search($keywords)
    {
        $this->db->like('cat_name',$keywords)->or_like('cat_code',$keywords,'after');
		return $this->db->get('category');
    }
    /**
    *insert data kategori baru
    */
    function add_category($data)
    {
        return $this->db->insert('category',$data);
    }    
    /**
    *update kategori
    */
    function update_category($param)
    {
        $query = 'update category set cat_name="'.$param['cat_name'].'", cat_desc="'.$param['cat_desc'].'" where cat_code="'.$param['cat_code'].'"';
        return $this->db->query($query);
    }
}
//end of file gudang_model.php