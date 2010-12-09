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
}