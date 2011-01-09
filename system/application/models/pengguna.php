<?php
/**
* Model untuk pengguna
* @author: PuRwa
* Digunakan untuk CRUD pengguna de el el
*/
class Pengguna extends Model {
    /**
    *Default Controller
    */
    function Pengguna() 
    {
        parent::Model();
    }
    /**
    *get all pengguna
    */
    function get_all()
    {
        $query = 'select * from pengguna p left join operator op on p.p_id = op.op_id order by p.p_id';
        return $this->db->query($query);
    }
    /**
    *ambil data pengguna berdasar username
    */
    function get_pengguna_by_username($username)
    {
        return $this->db->get_where('pengguna',array('p_username'=>$username));
    }
    /**
    * get pengguna
    */
    function get_pengguna($param)
    {
        $query = 'select * from pengguna p left join operator op on p.p_id = op.op_id where p.p_id="'.$param['p_id'].'" order by p.p_id';
        return $this->db->query($query);
    }
    /**
    * tambah pengguna
    */
    function insert($data)
    {
        return $this->db->insert('pengguna',$data);
    }
    /**
    * tambah operator
    */
    function insert_operator($data)
    {
        return $this->db->insert('operator',$data);
    }
    /**
    *update pengguna
    */
    function update_pengguna($data,$p_id)
    {
        $this->db->where('p_id',$p_id);
        return $this->db->update('pengguna',$data);
    }
    /**
    *update operator
    */
    function update_operator($data,$op_id)
    {
        $this->db->where('op_id',$op_id);
        return $this->db->update('operator',$data);
    }
    /**
    *update passwd
    */
   
}

/*End of file pengguna.php*/
/*Location: system/application/model/pengguna.php */