<?php
/**
* Log Model
* @author : Purwa
*/
class Log_transaksi extends Model {
	/**
    *Default constructor
    */
    function Log_transaksi()
	{
		parent::Model();
	}
	/**
    *Insert log
    */
    function insert($data)
    {
        return $this->db->insert('log_transaksi',$data);
    }
}
//end of file log_transaksi.php