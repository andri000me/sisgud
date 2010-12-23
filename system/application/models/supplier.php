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
    /**
    *Get supplier this day
    */
    function get_supplier_have_mutasi($role)
    {
        if($role != 'admin')
        {
            $query = 'select * from supplier where supplier.sup_code in(select item.sup_code from item left join item_mutasi on item.item_code = item_mutasi.item_code where item_mutasi.status_print_mutasi=0 group by item.sup_code)';
        }
        else
        {
            $query = 'select * from supplier where supplier.sup_code in(select item.sup_code from item left join item_mutasi on item.item_code = item_mutasi.item_code group by item.sup_code)';
        }
        return $this->db->query($query);
    }
    /**
    *insert supplier
    */
    function insert($data)
    {
        return $this->db->insert('supplier',$data);
    }
    /**
    * cari supplier
    */
    function search($keywords)
    {
        $this->db->like('sup_name',$keywords)->or_like('sup_code',$keywords,'after');
        return $this->db->get('supplier');
    }
    /**
    *update supplier
    */
    function update($data)
    {
        $query = 'update supplier set sup_name="'.$data['sup_name'].'", sup_address="'.$data['sup_address'].'", sup_phone="'.$data['sup_phone'].'", sup_type="'.$data['sup_type'].'" where sup_code = "'.$data['sup_code'].'"';
        return $this->db->query($query);
    }
}
//end of file supplier.php