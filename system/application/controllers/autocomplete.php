<?php
/**
*Auto compete contrroller
*Handling ajax request for autocomplete text box
*/
class Autocomplete extends Controller {
	function Autocomplete()
	{
		parent::Controller();
		$this->load->model('autocomplete_model', 'autocomplete');
	}
	function autocomplete_supplier($key="")
	{
		$query = $this->autocomplete->get_supplier($key);
		foreach($query->result() as $row)
		{
			echo ucwords($row->sup_name).'|'.$row->sup_code.chr(10);
		}
	}
	function autocomplete_item($key="")
	{
		$query = $this->autocomplete->get_item($key);		
		foreach($query->result() as $row)
		{
			echo ucwords($row->item_code).'|'.$row->item_name.'|'.$row->item_qty_stock.chr(10);
		}
	}
}
//end of file Aucomplete controller