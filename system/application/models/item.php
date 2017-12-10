<?php
/**
*Model Item
*Handling database transaction with database table 'Item'
*/
class Item extends Model {
	function Item()
	{
		parent::Model();
	}
	/**
	*retrieving data item with parameter
	*/
	function get_item_by_cat($cat_code)
	{
        $this->db->order_by('item_code','desc');
		return $this->db->get_where('item',array('cat_code'=>$cat_code));
	}
    /*
    * ambildata item berdasar kode klompok barang dan tahun
    * untuk generate kode item baru
    */
    function get_item_by_catnyear($catnyear)
    {
        $this->db->like('item_code',$catnyear,'after')->order_by('item_code','desc');
        return $this->db->get('item');
    }
    /**
    *get item
    */
    function get_item($param)
    {
        return $this->db->get_where('item',$param);
    }
    /**
    *ambil barang sisa mutasi
    */
    function get_sisa($keywords='')
    {
        $query = 'select i.*, sm.kode_mutasi from item i left join sisa_mutasi sm on i.item_code = sm.item_code 
        where i.item_qty_stock > 0 and i.item_hj > 0 and (i.item_code like "'.$keywords.'%" or i.item_name like "'.$keywords.'%") group by i.item_code';
        return $this->db->query($query);
    }
    /**
    *insert new item
    *@param : $data -> array item data
    */
    function add_item($data)
    {
        return $this->db->insert('item',$data);
    }
    /*
    * update item setelah mutasi keluar
    * kurangi stok dan isi harga jual
    */
    function update_item($param)
    {
        $query = 'update item set item_hj="'.$param['item_hj'].'", item_qty_stock="'.$param['item_qty_stok'].'" where item_code="'.$param['item_code'].'"';
        return $this->db->query($query);
    }
    /**
    *update item setelah retur, nambahin stok barang
    */
    function update_after_retur($param)
    {
        $query = 'update item set item_qty_stock = item_qty_stock + '.$param['quantity'].' where item_code="'.$param['item_code'].'"';
        return $this->db->query($query);
    }
    /**
    *Fungsi untuk search item
    */
    function search_item($param)
    {
        $query = 'select * from (select item.*,supplier.sup_name from item left join supplier on item.sup_code=supplier.sup_code 
                where item.item_code like "'.$param['keywords'].'%" or item.item_name like"'.$param['keywords'].'%") as search 
                left join category on search.cat_code = category.cat_code';
        return $this->db->query($query);
    }
    /**
    * fungsi untuk edit item yang salah2
    * opsi 1: operator
    * opsi 2: supervisor
    */
    function edit_item($param, $opsi)
    {
        //opsi 1 ubah table item dan item mutasi        
        if($opsi == 1)
        {
            if($this->edit_item_mutasi($param))
            {
                $query = 'update item set item_name="'.$param['item_name'].'", item_hp="'.$param['item_hp'].'", item_qty_stock="'.$param['quantity'].'", 
                item_qty_total="'.$param['quantity'].'", item_hm = ('.$param['item_hp'].'*'.$param['quantity'].'), sup_code="'.$param['sup_code'].'" 
                where item_code="'.$param['item_code'].'"';
            }
        }
        //opsi 2 ubah table item saja
        else if($opsi==2)
        {
            $query = 'update item set item_name="'.$param['item_name'].'", sup_code="'.$param['sup_code'].'", item_hj="'.$param['item_hj'].'" where item_code="'.$param['item_code'].'"';
        }
        return $this->db->query($query);
    }
    /**
    * fungsi untuk edit item mutasi
    */
    function edit_item_mutasi($param)
    {
        $query = 'update item_mutasi set sup_code="'.$param['sup_code'].'", qty="'.$param['quantity'].'" where item_code="'.$param['item_code'].'"';
        return $this->db->query($query);
    }
    /**
    * hapus item / barang
    */
    function hapus($item_code)
    {
    	//hapus di table item_distribusi
    	$sql = 'delete from item_distribution where item_code = "'.$item_code.'"';
    	$this->db->query($sql);
    	//hapus di table item_mutasi
    	$sql = 'delete from item_mutasi where item_code="'.$item_code.'"';
    	$this->db->query($sql);
    	//hapus di table item
        $this->db->where('item_code',$item_code);
        return $this->db->delete('item');
    }

    function delete_item($items)
    {
        $this->db->where_in('item_code', $items);
        return $this->db->delete('item');
    }
}
//end of file gudang_model.php