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
    *update status print jadi 1
    */
    function update_status($param)
    {
        $query = 'update sisa_mutasi set status_print_mutasi = 1, kode_mutasi="'.$param['kode_mutasi'].'" where item_code="'.$param['item_code'].'"';
        return $this->db->query($query);
    }
}
//end of sisa mutasi