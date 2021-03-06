<?php
/**
*Auto compete contrroller
*Handling ajax request for autocomplete text box
*/
class Autocomplete extends Controller {
    /**
    *Default constructor
    */
	function Autocomplete()
	{
		parent::Controller();
		$this->load->model('autocomplete_model', 'autocomplete');
	}
    /**
    *autocomplete supplier
    */
	function autocomplete_supplier($key="")
	{
		$query = $this->autocomplete->get_supplier($key);
		foreach($query->result() as $row)
		{
			echo ucwords($row->sup_name).'|'.$row->sup_code.chr(10);
		}
	}
    /*
    * auto complete item for mutasi keluar
    */
	function autocomplete_item($key="")
	{
        //ketik manual
        if(!empty($key))
        {
            $query = $this->autocomplete->get_item_for_mutasi_keluar($key);		
            foreach($query->result() as $row)
            {
                echo ucwords($row->item_code).'|'.$row->item_name.'|'.$row->item_qty_stock.'|'.ucwords($row->sup_name).'|'.number_format($row->item_hj,0,',','.').',-'.'|'.$row->item_hp.'|'.$row->item_hj.chr(10);
            }
        }
        //scan
        else 
        {
            $item_code = $this->input->post('item_code');
            $query = $this->autocomplete->get_item_for_mutasi_keluar($item_code);
            if($query->num_rows() == 1)
            {
                $row = $query->row();
                $data = array(
                    'item_code'=>$row->item_code,
                    'item_name'=>$row->item_name,
                    'item_qty_stock'=>$row->item_qty_stock,
                    'sup_name'=>ucwords($row->sup_name),
                    'item_hj'=>$row->item_hj,
                    'item_hp'=>number_format($row->item_hp,0,',','.').',-'
                );
                echo json_encode($data);
            }
            else
            {
                echo 0;
            }
        }
	}
    /*
    * auto complete item for mutasi hadiah
    */
	function autocomplete_hadiah($key="")
	{
		$hadiah=config_item('hadiah');
        if(in_array($key, $hadiah) );
        {
            $query = $this->autocomplete->get_item_for_mutasi_keluar($key);		
            foreach($query->result() as $row)
            {
                echo ucwords($row->item_code).'|'.$row->item_name.'|'.$row->item_qty_stock.'|'.ucwords($row->sup_name).'|'.number_format($row->item_hj,0,',','.').',-'.'|'.$row->item_hp.'|'.$row->item_hj.chr(10);
            }
        }
	}
    /*
    * auto complete item for retur
    */
	function autocomplete_retur($key="")
	{
        //ketik manual
        if(!empty($key)) 
        {
            $query = $this->autocomplete->get_item_for_retur($key);		
            foreach($query->result() as $row)
            {
                echo ucwords($row->item_code).'|'.$row->item_name.'|'.$row->item_qty_stock.'|'.ucwords($row->sup_name).'|'.number_format($row->item_hj,0,',','.').',-'.chr(10);
            }
        }
        //scan
        else 
        {
            $item_code = $this->input->post('item_code');
            $query = $this->autocomplete->get_item_for_retur($item_code);
            if($query->num_rows() == 1)
            {
                $row = $query->row();
                $data = array(
                    'item_code'=>$row->item_code,
                    'item_name'=>$row->item_name,
                    'item_qty_stock'=>$row->item_qty_stock,
                    'sup_name'=>ucwords($row->sup_name),
                    'item_hj'=>number_format($row->item_hj,0,',','.').',-'
                );
                echo json_encode($data);
            }
            else 
            {
                echo 0;
            }
        }
	}
    /**
    *autocomplete shop
    */
	function autocomplete_shop($key="")
	{
		$query = $this->autocomplete->get_shop($key);
		foreach($query->result() as $row)
		{
			echo ucwords($row->shop_name).'|'.$row->shop_code.chr(10);
		}
	}
    /** autocomplete supplier by code, for add new supplieer
    */
    function autocomplete_sup_add($key="")
    {
        $query = $this->autocomplete->get_last_sup($key);
        if($query->num_rows() > 0)
        {
            $last_sup = $query->row();
            //formating sup_code
            $sup_code = $last_sup->sup_code;
            $num = substr($sup_code,1,3);
            $num++;
            if(strlen($num) == 1)
                $num = '00'.$num;
            else if(strlen($num)== 2)
                $num = '0'.$num;
            $new_code = substr($sup_code,0,1).$num;
            echo 'Supplier Baru |'.$new_code;            
        }
        else
        {
            echo 'Supplier Baru|'.strtoupper($key).'001';
        }
    }
}
//end of file Aucomplete controller