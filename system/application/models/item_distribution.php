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
    * update status untuk akumulasi cetak label
    */
    function update_for_acc($item_code,$status)
    {
        if($status == 2)
        {
            //$this->db->where(array('status'=>0,'dist_code'=>'0','item_code'=>$item_code));
            $this->db->where(array('status'=>0,'item_code'=>$item_code));
        }
        else if($status == 0)
        {
            //$this->db->where(array('status'=>2,'dist_code'=>'0','item_code'=>$item_code));
            $this->db->where(array('status'=>2,'item_code'=>$item_code));
        }
        return $this->db->update('item_distribution',array('status'=>$status));
    }
    /**
    * Ambil data distribusi barang ke toko-toko
    */
    function get_item_distribution($item_code) 
    {
        $query = 'select item_code,shop_code,sum(quantity) as total from item_distribution  where item_code = "'.$item_code.'" group by shop_code';
        return $this->db->query($query);
    }
    /**
    * Ambil semua item yang dalam status terakumulasi
    */
    function get_item_accumulated($sup_code)
    {        
        $this->db->select('item.item_code')->from('item_distribution')->join('item','item.item_code = item_distribution.item_code');
        //$this->db->where(array('dist_code'=>'0','status'=>2,'sup_code'=>$sup_code))->group_by('item.item_code')->order_by('item_distribution.id');
        $this->db->where(array('status'=>2,'sup_code'=>$sup_code))->group_by('item.item_code')->order_by('item_distribution.id');
        return $this->db->get();
    }
    /**
    *ambil supplier yang belum dicetak labelnya
    */
    function get_supplier_for_printing()
    {
        $query = 'select * from item_distribution ids left join item i on ids.item_code = i.item_code where ids.status != 1 group by i.sup_code';
        return $this->db->query($query);
    }
    /*
    *get item by supplier
    */
    function get_item_for_printing($sup_code)
    {
        $query = 'select sum(ids.quantity) as qty,ids.status,i.* 
                    from item_distribution ids 
                    left join item i on ids.item_code=i.item_code where i.sup_code = "'.$sup_code.'" and ids.status != 1 
                    group by ids.item_code order by ids.id';
        return $this->db->query($query);
    }
    /**
    *
    */
    /**
    * ambil data barang untuk dieksport ke file .txt yang akan dipake untuk print barcode
    */
    function get_item_for_exporting($item_code)
    {
        $query = 'select * from (select ids.dist_out,ids.shop_code,ids.quantity,i.* 
                    from item_distribution ids 
                    left join item i on ids.item_code=i.item_code where i.item_code = "'.$item_code.'" and ids.status=2) as cetak
                left join shop on cetak.shop_code = shop.shop_code';
                    
        return $this->db->query($query);
    }
    /**
    * fungsi ambil data item untuk preview sebelum export
    * data yang dipreview untuk dieksport adalah yang udah di cetak bon nya
    */
    function get_item_for_shop($shop_code)
    {
        $query = 'select ids.item_code, i.item_name, i.cat_code,i.item_hj, ids.item_disc, sum(ids.quantity) as quantity 
                    from item_distribution ids left join item i on ids.item_code=i.item_code 
                    where ids.shop_code = "'.$shop_code.'" and ids.export=0 and dist_code != "0" 
        			group by ids.item_code order by ids.id';
        return $this->db->query($query);
    }
    /**
    * hitung jumlah bon yang akan diekport datanya
    */
    function count_bon_export($shop_code)
    {
        $query = 'select ids.dist_code, ids.shop_code, count(ids.item_code) as jml_item, sum(quantity) as total_item from item_distribution ids 
                    where ids.shop_code = "'.$shop_code.'" and ids.export=0 and dist_code != "0" group by ids.dist_code';
        return $this->db->query($query);
    }
    /**
    * ambil export berdasarkan kode exportnya
    */
    function get_item_export($param)
    {
        if(isset($param['export']))
        {
            $query = 'select ids.item_code, i.item_name, i.cat_code,i.item_hj, ids.item_disc, sum(ids.quantity) as quantity 
                        from item_distribution ids left join item i on ids.item_code=i.item_code 
                        where ids.export="'.$param['export'].'" group by ids.item_code order by ids.item_code';
        }
        else if(isset($param['dist_code']) && isset($param['shop_code']))
        {
            $query = 'select ids.item_code, i.item_name, i.cat_code,i.item_hj, ids.item_disc, sum(ids.quantity) as quantity 
                        from item_distribution ids left join item i on ids.item_code=i.item_code 
                        where ids.dist_code="'.$param['dist_code'].'" and ids.shop_code="'.$param['shop_code'].'" group by ids.item_code order by ids.item_code';
        }
        return $this->db->query($query);
    }
    /**
    *getting item for bon
    */
    function get_item_for_bon($shop_code,$dist_out)
    {
        $query = 'select ids.*,i.*,sum(ids.quantity) as quantity from item_distribution ids left join item i on ids.item_code = i.item_code 
                where ids.shop_code = "'.$shop_code.'" and ids.dist_code="0" and ids.status=1 and dist_out="'.$dist_out.'" group by ids.id';
        return $this->db->query($query);
    }
    /**
    *ambil item untuk cetak bon pdf
    */
    function get_item_for_pdf($param)
    {
        $this->db->select('item_distribution.*,sum(quantity) as quantity');
        $this->db->group_by('item_code')->order_by('item_code','asc');        
        return $this->db->get_where('item_distribution',$param);
    }
    /**
    *update status
    */
    function update_status($param)
    {
        $query = 'update item_distribution set status = 1 where status = 2 and item_code = "'.$param['item_code'].'"';
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
    	$sql = 'select * from item_distribution where dist_code = "'.$param['dist_code'].'" and shop_code = "'.$param['shop_code'].'"';
    	$count = $this->db->query($sql);
    	if($count->num_rows() == 0)
    	{
        	$query = 'update item_distribution ids set ids.dist_code="'.$param['dist_code'].'" 
        			where ids.status=1 and ids.dist_code= "0" and ids.shop_code="'.$param['shop_code'].'"
        			and ids.dist_out="'.$param['dist_out'].'"';
        	return $this->db->query($query);
    	}
    }
    /**
    *ambil bon yang dimiliki toko
    */
    function get_bon_by_toko($shop_code,$tgl)
    {
        if(!empty($tgl))
            $tgl = 'and ids.dist_out="'.$tgl.'"';
        else
            $tgl = '';
        $query = 'select shop.shop_name,shop.shop_address, dist_code,dist_out, count(item_code) as jenis_brg,sum(quantity) as jumlah_brg 
                from item_distribution ids left join shop on ids.shop_code = shop.shop_code 
                where ids.shop_code ="'.$shop_code.'" '.$tgl.' and ids.dist_code != "0"
                group by dist_code order by dist_out desc';
        return $this->db->query($query);
    }
    /**
    *Ambil kode bon yang terakhir
    */
    function get_last_dist_code($shop_code)
    {
        $this->db->select('dist_code')->group_by('dist_code')->order_by('dist_code','desc')->limit(1);
        return $this->db->get_where('item_distribution',array('shop_code'=>$shop_code));
    }
    
    /**
     * hitung jumlah bon yang harus dibuat, beda hari dibedakan bonnya.
     * Cetak bon di lakukan berdasarkan urutan tanggal. 
     * Ex: salah satu toko belum cetrak bon selama 2 hari dan ketika hari ketiga
     * ingin cetak bon maka bon tersebut di kelompokkan berdasarkan tanggal. 
     * Begitu juga untuk isi dari import data barang. 
     * Sehingga memfasilitasi untuk pencetakan bon sebanyak 2 kali bon yang berbeda dalam satu hari yang sama 
     * dan di bedakan dari nomor bon saja
     */
    function count_bon($shop_code)
    {
    	$sql = 'select dist_out from item_distribution where shop_code = "'.$shop_code.'" and dist_code="0" group by dist_out order by dist_out';
    	//echo $sql;
    	return $this->db->query($sql);
    }
    
    /**
     * Ambil item untuk dicetak ulang label
     */
    function get_cetak_ulang_label($dist_out)
    {
    	$sql = 'select dist.*,s.sup_name from (
    				select i.sup_code, count(i.item_code) as jenis, sum(ids.quantity) as jumlah 
    				from item_distribution ids left join item i on ids.item_code = i.item_code 
    				where ids.dist_out="'.$dist_out.'" and ids.status=1 group by i.sup_code ) dist 
    			left join supplier s on dist.sup_code = s.sup_code';
    	return $this->db->query($sql);
    }
    
    /**
     * Update status label kembali ke nol untuk cetak ulang
     */
    function reset_status_label($sup_code, $dist_out, $shop_code)
    {
    	$sql = 'update item_distribution set status=0 where item_code in(
    				select item_code from item where sup_code = "'.$sup_code.'"
    			) and dist_out = "'.$dist_out.'" and shop_code="'.$shop_code.'"';
    	return $this->db->query($sql);
    }
    
    /**
     * 
     * Recapitulation of item distribution
     * @param Array $param
     */
    function recap_distribution($param)
    {
    	if(!empty($param['item_code']))
    		$where = 'where item.item_code = "'.$param['item_code'].'"';
    	else if(!empty($param['sup_code']))
    		$where = 'where item.sup_code = "'.$param['sup_code'].'"';
    	else if(!empty($param['cat_code']))
    		$where = 'where item.cat_code = "'.$param['cat_code'].'"';	
    	else $where = '';
    	
    	$sql = 'select item.* from (select item_code from item_distribution where dist_out >= "'.$param['tgl_awal'].'" 
    			and dist_out <= "'.$param['tgl_akhir'].'" group by item_code) dist left join item on 
    			dist.item_code = item.item_code '.$where.' order by item_code';
    	//echo $sql;
    	return $this->db->query($sql);
    }

    /**
     * Statistik distribusi barang berdasarkan supplier per toko
     */
    function stat_item_dist_sup($param)
    {
        $sql = 'select i.sup_code, sum(id.quantity) as qty, sum(id.quantity*i.item_hj) as rupiah
        from item_distribution id left join item i on id.item_code = i.item_code
        where id.shop_code = "'.$param['shop_code'].'" and id.dist_out >="'.$param['from'].'" and id.dist_out <= "'.$param['to'].'"
        group by i.sup_code order by rupiah desc';
        return $this->db->query($sql);
    }

    /**
     * Statistik distribusi barang berdasarkan kelompok barang per toko
     */
    function stat_item_dist_cat($param)
    {
        $sql = 'select i.cat_code, sum(id.quantity) as qty, sum(id.quantity*i.item_hj) as rupiah
        from item_distribution id left join item i on id.item_code = i.item_code
        where id.shop_code = "'.$param['shop_code'].'" and id.dist_out >="'.$param['from'].'" and id.dist_out <= "'.$param['to'].'"
        group by i.cat_code order by rupiah desc';
        return $this->db->query($sql);
    }
}