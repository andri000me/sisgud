<?php
/**
*Model Category
*Handling database transaction with database table 'Category'
*/
class Shop extends Model {
	function Shop()
	{
		parent::Model();
	}
	/**
	*retrieving shop
    * by shop_code
    * all shop except obral and rusak
	*/
	function get_shop($shop_code='')
	{
        if(!empty($shop_code))
        {
		    return $this->db->get_where('shop',array('shop_code'=>$shop_code,'flag_hapus'=> 0));
        }
        else
        {
            $this->db->where('shop_cat !=','OBRAL');
            $this->db->where('shop_cat !=','RUSAK');
            return $this->db->get_where('shop',array('flag_hapus'=>0));
        }
	}
    /**
    * Ambil semua toko tak terkecuali
    */
    function get_all_shop()
    {
        return $this->db->get_where('shop',array('flag_hapus'=>0));
    }
    /**
    * ambil data toko berdasarkan kategori toko
    */
    function get_shop_by_cat($shop_cat)
    {
        return $this->db->get_where('shop',array('flag_hapus'=>0,'shop_cat'=>$shop_cat));    
    }
    /**
    *insert / tambah toko baru
    */
    function insert($data)
    {
        return $this->db->insert('shop',$data);        
    }
    /**
    * function cari toko
    */
    function cari($keywords='')
    {
        $query = 'select shop.shop_code, shop.shop_name, sum(ids.quantity) as total from shop 
                left join item_distribution ids on shop.shop_code = ids.shop_code 
                where (shop.shop_code like "'.$keywords.'%" or shop.shop_name like "'.$keywords.'%") and shop.flag_hapus = 0
                group by shop.shop_code order by shop.shop_code';
        return $this->db->query($query);
    }
    /**
    *function cari retur
    */
    function cari_retur($shop_code)
    {
        $query = 'select shop.shop_code, shop.shop_name, sum(ir.quantity) as retur from shop 
                left join item_retur ir on shop.shop_code=ir.shop_code  where ir.shop_code="'.$shop_code.'" group by ir.shop_code';
        return $this->db->query($query);
    }
    /**
    *fungsi detail
    */
    function detail($shop_code)
    {
        $query = 'select shop.*, sum(ids.quantity) as total from shop 
                left join item_distribution ids on shop.shop_code = ids.shop_code  
                where shop.shop_code = "'.$shop_code.'"
                group by ids.shop_code order by shop.shop_code';
        return $this->db->query($query);
    }
    /**
    *fungsi ubah toko
    */
    function update($data)
    {
        //$query = 'update shop set shop_initial="'.$data['shop_initial'].'", shop_name="'.$data['shop_name'].'", shop_phone="'.$data['shop_phone'].'", shop_address="'.$data['shop_address'].'", shop_supervisor="'.$data['shop_supervisor'].'" where shop_code="'.$data['shop_code'].'"';
        $this->db->where('shop_code',$data['shop_code']);
        return $this->db->update('shop',$data);
    }
    /**
    *fungsi cari stok toko
    */
    function search_stok($keywords, $shop_code)
    {
        $query = 'select stok_toko.*,retur_toko.retur from (select i.*, sum(ids.quantity) as stok 
                                from item_distribution ids left join item i on ids.item_code = i.item_code 
                                where ids.shop_code = "'.$shop_code.'" group by ids.item_code) as stok_toko 
                    left join (select ir.item_code,sum(ir.quantity) as retur 
                                from item_retur ir where ir.shop_code = "'.$shop_code.'" group by ir.item_code) as retur_toko
                    on stok_toko.item_code = retur_toko.item_code
                    where stok_toko.item_code like "'.$keywords.'%" or stok_toko.item_name like "'.$keywords.'%"';
        return $this->db->query($query);
    }
    /**
    * function untuk hapus
    */
    function hapus($param)
    {
        $this->db->where('shop_code',$param['shop_code']);
        return $this->db->update('shop',array('flag_hapus'=>1));
    }
}
//end of file gudang_model.php