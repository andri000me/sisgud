<?php
/**
*Model: Item Distribution 
*@author: PuRwa
*Ngurusin pembagian barang ke toko toko
*/
class Item_distribution extends Model {
    /**
    *Default Constructr
    */
    function Item_distribution()
    {
        parent::Model();
    }
    /**
    *insert item mutasi
    */
    function insert_item_distribution($data)
    {
        return $this->db->insert('item_distribution',$data);
    }
    /**
    *ambil supplier yang belum dicetak labelnya
    */
    function get_supplier_for_printing()
    {
        $query = 'select * from item_distribution ids left join item i on ids.item_code = i.item_code where ids.status = 0 group by i.sup_code';
        return $this->db->query($query);
    }
    /*
    *get item by supplier
    */
    function get_item_for_printing($sup_code)
    {
        $query = 'select sum(ids.quantity) as qty,i.* 
                    from item_distribution ids 
                    left join item i on ids.item_code=i.item_code where i.sup_code = "'.$sup_code.'" and ids.status=0 
                    group by ids.item_code';
        return $this->db->query($query);
    }
    /**
    * ambil data barang untuk dieksport ke file .txt yang akan dipake untuk print barcode
    */
    function get_item_for_exporting($item_code)
    {
        $query = 'select * from (select ids.dist_out,ids.shop_code,ids.quantity,i.* 
                    from item_distribution ids 
                    left join item i on ids.item_code=i.item_code where i.item_code = "'.$item_code.'" and ids.status=0) as cetak
                left join shop on cetak.shop_code = shop.shop_code';
                    
        return $this->db->query($query);
    }
    /**
    * fungsi ambil data item untuk preview sebelum export
    */
    function get_item_for_shop($shop_code)
    {
        $query = 'select ids.item_code, i.item_name, i.cat_code,i.item_hj, ids.item_disc, ids.quantity 
                    from item_distribution ids left join item i on ids.item_code=i.item_code 
                    where ids.shop_code = "'.$shop_code.'" and ids.export=0';
        return $this->db->query($query);
    }
    /**
    * ambil export berdasarkan kode exportnya
    */
    function get_item_export($param)
    {
        $query = 'select ids.item_code, i.item_name, i.cat_code,i.item_hj, ids.item_disc, ids.quantity 
                    from item_distribution ids left join item i on ids.item_code=i.item_code 
                    where ids.export="'.$param['export'].'"';
        return $this->db->query($query);
    }
    /**
    *getting item for bon
    */
    function get_item_for_bon($shop_code)
    {
        $query = 'select * from item_distribution ids left join item i on ids.item_code = i.item_code where ids.shop_code = "'.$shop_code.'" and ids.dist_code=0 and ids.status=1';
        return $this->db->query($query);
    }
    /**
    *ambil item untuk cetak bon pdf
    */
    function get_item_for_pdf($param)
    {
        return $this->db->get_where('item_distribution',$param);
    }
    /**
    *update status
    */
    function update_status($param)
    {
        $query = 'update item_distribution set status = 1 where status = 0 and item_code = "'.$param['item_code'].'"';
        return $this->db->query($query);
    }
    /**
    * update status export
    */
    function update_status_export($param)
    {
        $query = 'update item_distribution set export = "'.$param['export'].'" where export = 0 and shop_code = "'.$param['shop_code'].'"';
       return $this->db->query($query);
    }
    /**
    * bikin bon untuk toko
    * shop_code, dist_code
    */
    function create_bon($param)
    {
        $query = 'update item_distribution ids set ids.dist_code="'.$param['dist_code'].'" where ids.status=1 and ids.dist_code= 0 and ids.shop_code="'.$param['shop_code'].'"';
        return $this->db->query($query);
    }
    /**
    *ambil bon yang dimiliki toko
    */
    function get_bon_by_toko($shop_code)
    {
        $query = 'select shop.shop_name,shop.shop_address, dist_code,dist_out, count(item_code) as jenis_brg,sum(quantity) as jumlah_brg 
                from item_distribution ids left join shop on ids.shop_code = shop.shop_code 
                where ids.shop_code ="'.$shop_code.'"
                group by dist_code order by dist_out desc';
        return $this->db->query($query);
    }
}