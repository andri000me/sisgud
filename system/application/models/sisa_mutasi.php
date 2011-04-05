<?php
/**
*Model: Sisa Mutasi 
*@author: PuRwa
*Ngurusin transaksi untuk mutasi yang sisa2
*/
class Sisa_mutasi extends Model {
    /**
    *Default Constructr
    */
    function Sisa_mutasi()
    {
        parent::Model();
    }
    /**
    *insert sisa mutasi untuk dicetak bonnnya untuk dibagi2 ke toko2
    */
    function insert_sisa_mutasi($data)
    {
        return $this->db->insert('sisa_mutasi',$data);
    }
    /*
    *remove sisa mutasi dari table, alias gak jadi dicetak
    */
    function  remove($data)
    {
        return $this->db->delete('sisa_mutasi',$data);
    }
    /**
    * ambil data untuk dicetak
    */
    function get_sisa_mutasi()
    {
        $this->db->order_by('item_code','asc');        
        return $this->db->get_where('sisa_mutasi', array('status_print_mutasi'=>0));
    }
    /**
    * ambil data untuk dicetak berdasar kode mutasi
    */
    function get_sisa_mutasi_by_kode($kode)
    {
        $this->db->order_by('item_code','asc');
        return $this->db->get_where('sisa_mutasi', array('kode_mutasi'=>$kode));
    }
    /**
    *update status print jadi 1
    */
    function update_status($param)
    {
        $query = 'update sisa_mutasi set status_print_mutasi = 1, kode_mutasi="'.$param['kode_mutasi'].'" where item_code="'.$param['item_code'].'"';
        return $this->db->query($query);
    }
    /**
    *Ambil data mutasi masuk pada tanggal tertentu, kelompokkan berdasarkan kode mutasi
    */
    function get_sisa_mutasi_by_date($param)
    {
        $query = 'select sisa_mutasi.*, count(item_code) as jml_barang, supplier.* from sisa_mutasi left join supplier on sisa_mutasi.sup_code = supplier.sup_code where sisa_mutasi.date_entry = "'.$param['tgl_mutasi'].'" group by kode_mutasi';
        return $this->db->query($query);
    }
    /**
    * Ambil data mutasi berdasarkan supplier, kelompokkan berdasarkan bon
    */
    function get_sisa_mutasi_by_supplier($sup_code)
    {
        $query = 'select sisa_mutasi.*, count(item_code) as jml_barang, supplier.* from sisa_mutasi left join supplier on sisa_mutasi.sup_code = supplier.sup_code where sisa_mutasi.sup_code = "'.$sup_code.'" group by kode_mutasi';
        return $this->db->query($query);
    }
}
//end of sisa mutasi