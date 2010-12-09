<?php
/**
*Name : Gudang
*Author : PuRwa
*version : 1.0 beta
*Description: This class is used to handle transcation which take place on storehouse
*/
class Training extends Controller {
	var $data;
	/**
	*Class constructor
	*/
	function Training()
	{
		parent::Controller();
		$this->load->helper(array('html','form','url'));		
	}
    function index()
    {
        $this->load->view('livesearch');
    }
    /**
    *Fungsi untuk membuat json_reader json_encode menggunakan extjs
    **/
    function js_list_item()
    {
        $query = $this->db->get('item');
        foreach($query->result() as $row)
        {
            $item = array(
                    'item_code'=>$row->item_code,
                    'item_name'=>$row->item_name,
                    'item_qty_stock'=>$row->item_qty_stock,
                );
            $item_list[] = $sup;
        }
        $rows = $query->num_rows();
        $data = json_encode($item_list);
        echo '({"total":"' . $rows . '","results":' . $data . '})'; 
    }
}
//End of Training Controller
//Location: application/controller/training.php