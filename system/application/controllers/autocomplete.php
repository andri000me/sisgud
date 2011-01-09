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
		$query = $this->autocomplete->get_item_for_mutasi_keluar($key);		
		foreach($query->result() as $row)
		{
			echo ucwords($row->item_code).'|'.$row->item_name.'|'.$row->item_qty_stock.'|'.ucwords($row->sup_name).'|'.number_format($row->item_hj,0,',','.').',-'.'|'.$row->item_hp.'|'.$row->item_hj.chr(10);
		}
	}
    /*
    * auto complete item for retur
    */
	function autocomplete_retur($key="")
	{
		$query = $this->autocomplete->get_item_for_retur($key);		
		foreach($query->result() as $row)
		{
			echo ucwords($row->item_code).'|'.$row->item_name.'|'.$row->item_qty_stock.'|'.ucwords($row->sup_name).'|'.number_format($row->item_hj,0,',','.').',-'.chr(10);
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
    
}
//end of file Aucomplete controller