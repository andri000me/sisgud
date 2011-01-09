<?php
/**
*Model: Item Retur 
*@author: PuRwa
*Ngurusin Retur barang dari toko msuk ke k gudang
*/
class Item_retur extends Model {
    /**
    *Default Constructr
    */
    function Item_retur()
    {
        parent::Model();
    }
    /**
    *insert item mutasi
    */
    function insert_item_retur($data)
    {
        return $this->db->insert('item_retur',$data);
    }
    /**
    * rekap retur barang
    */
    function rekap($param)
    {
        $query = 'select ir.*,i.item_name from item_retur ir
                left join item i on ir.item_code = i.item_code
                where i.sup_code="'.$param['sup_code'].'" and ir.retur_date >= "'.$param['tgl_awal'].'" and ir.retur_date <= "'.$param['tgl_akhir'].'" order by ir.retur_date desc, ir.shop_code';
        return $this->db->query($query);
    }
    /**
    * ambil data retur
    */
    function get_retur($param)
    {
        $query = 'select * from 
                    (select item.item_name,item.sup_code,item.cat_code,item.item_hj,item_retur.* from item_retur left join item on item_retur.item_code=item.item_code where item_retur.retur_code = "'.$param['retur_code'].'") as retur
                    left join shop on retur.shop_code = shop.shop_code';
        return $this->db->query($query);
    }
}
//End of item retur   