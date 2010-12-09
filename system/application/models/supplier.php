<?php
/**
*Suplier model
*All about suplier
*/
class Supplier extends Model {
	/**
    *Model constructor
    */
    function Supplier()
	{
		parent::Model();
	}
    /**
    *Get supplier data, per sup_code
    */
    function get_supplier($sup_code)
    {
        return $this->db->get_where('supplier',array('sup_code'=>$sup_code));
    }
}
//end of file supplier.php