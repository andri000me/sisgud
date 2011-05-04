<?php
/**
*Model: Item Mutasi 
*@author: PuRwa
*Ngurusin transaksi untuk mutasi masuk
*/
class Item_mutasi extends Model {
    /**
    *Default Constructr
    */
    function Item_mutasi()
    {
        parent::Model();
    }
    /**
    *insert item mutasi
    */
    function insert_item_mutasi($data)
    {
        return $this->db->insert('item_mutasi',$data);
    }
    /**
    *ambil data item mutasi untuk dicetak
    */
    function get_item_mutasi($param='')
    {
        if(isset($param['sup_code']))
        {
            $query = 'select * from item_mutasi left join item on item_mutasi.item_code=item.item_code where item.sup_code = "'.$param['sup_code'].'" and item_mutasi.status_print_mutasi=0 order by id';
        }
        else if(isset($param['kode_mutasi']))
        {
            $query = 'select * from item_mutasi left join supplier on item_mutasi.sup_code=supplier.sup_code where item_mutasi.kode_mutasi = "'.$param['kode_mutasi'].'" order by id';
        }
        return $this->db->query($query);
    }
    /**
    *update status_print_mutasi jadi 1 setelah print
    */
    function update_status($param)
    {
        $this->db->where(array('sup_code'=>$param['sup_code'],'status_print_mutasi'=>0));
        return $this->db->update('item_mutasi',array('status_print_mutasi'=>1));
    }
    /**
    *Ambil data mutasi masuk pada tanggal tertentu, kelompokkan berdasarkan kode mutasi
    */
    function get_item_mutasi_by_date($param)
    {
        $query = 'select item_mutasi.*, count(item_code) as jml_barang, supplier.* from item_mutasi left join supplier on item_mutasi.sup_code = supplier.sup_code where item_mutasi.date_entry = "'.$param['tgl_mutasi'].'" group by kode_mutasi';
        return $this->db->query($query);
    }
    /**
    * Ambil data mutasi pada tanggal bon tertentu, kelompokkan berdasarkan kode mutasi
    */
    function get_item_mutasi_by_bon($tgl_bon)
    {
        $query = 'select item_mutasi.*, count(item_code) as jml_barang, supplier.* from item_mutasi left join supplier on item_mutasi.sup_code = supplier.sup_code where item_mutasi.date_bon = "'.$tgl_bon.'" group by kode_mutasi';
        return $this->db->query($query);
    }
    /**
    * Ambil data mutasi berdasarkan supplier, kelompokkan berdasarkan bon
    */
    function get_item_mutasi_by_supplier($sup_code)
    {
        $query = 'select item_mutasi.*, count(item_code) as jml_barang, supplier.* from item_mutasi left join supplier on item_mutasi.sup_code = supplier.sup_code where item_mutasi.sup_code = "'.$sup_code.'" group by kode_mutasi';
        return $this->db->query($query);
    }
    /*
    *ambil data mutasi masuk berdasarkan kode mutasi    
    */
    function get_item_mutasi_by_code($param)
    {
        $query = 'select * from item_mutasi left join item on item_mutasi.item_code=item.item_code where item_mutasi.kode_mutasi = "'.$param['kode_mutasi'].'"';
        return $this->db->query($query);
    }
}