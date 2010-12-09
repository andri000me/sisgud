<?php
/**
*Name : Toko
*Author : PuRwa
*version : 1.0 beta
*Description: This class is used to handle management of Store/Toko
*/
class Toko extends Controller {
	var $data;
	/**
	*Class constructor
	*/
	function Toko()
	{
		parent::Controller();
		$this->load->helper(array('html','form','url'));
		$link1 = array(
			'href' => 'css/default.css',
			'rel' => 'stylesheet',
			'type' => 'text/css',
			'media' => 'screen'
		);
		$link2 = array(
			'href' => 'css/menu.css',
			'rel' => 'stylesheet',
			'type' => 'text/css',
			'media' => 'screen'
		);
		$this->data['link_tag'] = link_tag($link1).link_tag($link2);
		$this->data['page_title'] = 'Sistem Inventori Gudang';
		$this->data['pages']='toko';
		if($this->session->userdata('logged_in') != TRUE)
		{
		     redirect('/home/', 'refresh');
		}
		$this->load->helper('sisgud');
		$this->data['now_date'] = get_date();
		$this->data['userinfo'] = get_userinfo($this);
	}
	/**
	*Default function index. First call by class
	*/
	function index()
	{
		
		$this->load->view('index',$this->data);
	}
	/**
	*Method untuk menambahkan toko baru
	*/
	function tambah()
	{
        $this->data['page_title'] .= ':. Menambah Toko';
		if(isset($_POST['submit_tambah_toko']))
        {
            if($this->validate_form_tambah_toko())
            {
                $this->data['shop_code'] = $this->input->post('shop_code');
                $this->data['shop_name'] = $this->input->post('shop_name');
                $this->data['shop_initial'] = $this->input->post('shop_initial');
                $this->data['shop_address'] = $this->input->post('shop_address');
                $this->data['shop_phone'] = $this->input->post('shop_phone');
                $this->data['shop_supervisor'] = $this->input->post('shop_supervisor');
                //insert data ke database
                if($this->insert_data())
                {
                    $this->data['notify']='Data berhasil ditambahkan';
                }
                else
                {
                    $this->data['notify']='Gagal menambahkan data';
                }
            }
        }        
		$this->load->view('tok_tambah',$this->data);
	}
    /**
    *Method untuk insert data
    **/
    function insert_data()
    {
        $data = array(
                    'shop_code'=>$this->data['shop_code'],
                    'shop_name'=>$this->data['shop_name'],
                    'shop_address'=>$this->data['shop_address'],
                    'shop_initial'=>$this->data['shop_initial'],
                    'shop_phone'=>$this->data['shop_phone'],
                    'shop_supervisor'=>$this->data['shop_supervisor']
                );
        if($this->db->insert('shop',$data))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    /**
    *Method untuk validasi form tambah toko
    **/
    function validate_form_tambah_toko()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('shop_code','kode toko','required');
        $this->form_validation->set_rules('shop_name','nama toko','required');
        $this->form_validation->set_rules('shop_initial','inisial toko','required');
        $this->form_validation->set_rules('shop_address','alamat toko','required');
        $this->form_validation->set_rules('shop_phone','nomor telepon','required');
        $this->form_validation->set_rules('shop_supervisor','supervisor','required');
        if($this->form_validation->run()==FALSE)
        {
            $this->data['err_vald'] = 'Terjadi beberapa kesalahan input :'.validation_errors();
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
	/**
	*Method untuk melihat stok toko
	*/
	function stok()
	{
		if(isset($_POST['submit_search_toko']))
		{
			//ambil data form
			$shop_code = $this->input->post('shop_code');
			$query = $this->db->get_where('shop',array('shop_code'=>$shop_code));
			$shop = $query->row();
			$this->data['shop_name'] = ucwords($shop->shop_name);
			$keywords = $this->input->post('keywords');
			$key = $this->input->post('key');
			if(!empty($keywords))
			{
				$sql = 'select toko.*,toko.stok_toko,(toko.stok_toko-retur.qty_retur) as sisa
					from (select item.item_code, item.sup_code, item.item_name, item.item_hj,  sum(item_distribution.quantity) as stok_toko
					from item, item_distribution where item.item_code = item_distribution.item_code and item_distribution.shop_code ="'.$shop_code.'" group by item.item_code) 
					as toko left join (select item_retur.item_code, sum(item_retur.quantity) as qty_retur from item_retur group by item_retur.item_code) as retur on toko.item_code = retur.item_code
					where toko.item_code like "%'.$keywords.'%" or toko.sup_code like "%'.$keywords.'%" or toko.item_name like "%'.$keywords.'%"';
			}
			else
			{
				$sql = 'select toko.*,toko.stok_toko,(toko.stok_toko-retur.qty_retur) as sisa
					from (select item.item_code, item.sup_code, item.item_name, item.item_hj,  sum(item_distribution.quantity) as stok_toko
					from item, item_distribution where item.item_code = item_distribution.item_code and item_distribution.shop_code ="'.$shop_code.'" group by item.item_code) 
					as toko left join (select item_retur.item_code, sum(item_retur.quantity) as qty_retur from item_retur group by item_retur.item_code) as retur on toko.item_code = retur.item_code';
			}
			$query = $this->db->query($sql);
			//echo $this->db->last_query();exit;
			$tr = '';
			foreach($query->result() as $row)
			{
				$temp = $this->db->get_where('supplier',array('sup_code'=>$row->sup_code));
				$sup = $temp->row();
				if(!empty($row->sisa))
				{
					$row->stok_toko = $row->sisa;
				}
				$tr .= '<tr>
				    <td>'.$row->item_code.'</td>
				    <td>'.$row->item_name.'</td>
				    <td>'.$row->stok_toko.'</td>
				    <td>-</td>
				    <td style="text-align:right">'.number_format($row->item_hj,'0',',','.').',- &nbsp;</td>
				    <td>'.ucwords($sup->sup_name).'</td>		
				</tr>';
			} 
			$this->data['tr'] = $tr;
			//echo $tr;exit;            
		}
		$this->list_toko();
		$this->data['page_title'] .= ':. Melihat Stok Toko';
		$this->load->view('tok_stok',$this->data);
	}
    /**
    *Membuat list toko
    **/
    function list_toko()
    {
        $query = $this->db->get('shop');            
        if($query->num_rows())
        {
            $this->data['list_toko'] ='<select name="shop_code">';
            foreach($query->result() as $row)
            {
                $this->data['list_toko'] .= '<option value="'.$row->shop_code.'">'.ucwords($row->shop_name).'</option>';
            }
            $this->data['list_toko'] .= '</select>';
            
        }
    }
	/**
	*Method untuk melihat detail toko
	*/
	function detail()
	{
		if(isset($_POST['submit_detail_toko']))
		{
			$query = $this->db->get_where('shop', array('shop_code'=>$this->input->post('shop_code')));
			$data = $query->row();
			//mecah alamat jadi dua
			$array = explode(" ", $data->shop_address);
			$alamat1='';
			for($i=0;$i<count($array)-1;$i++)
			{
				$alamat1 .= $array[$i].' ';
			}
			//mecah nomor telepon, nyelipin tanda kurung
			$kode_area = substr($data->shop_phone,0,3);
			$bag1 = substr($data->shop_phone,3,4);
			$bag2 =  substr($data->shop_phone,6,4);
			$this->data['shop_phone'] = '('.$kode_area.') '.$bag1.' '.$bag2;
			$this->data['alamat1'] = $alamat1;
			$this->data['alamat2'] =  $array[count($array)-1];
			$this->data['shop_code'] = $data->shop_code;
			$this->data['shop_name'] = $data->shop_name;
			$this->data['shop_initial'] = $data->shop_initial;
			$this->data['shop_supervisor'] = $data->shop_supervisor;			
		}
		$this->data['page_title'] .= ':. Melihat Detail Toko';
		$this->list_toko();
		$this->load->view('tok_detail',$this->data);
	}
	/**
	*Method untuk mengubah data toko, 
	* Data yang diubah adalah supervisor dan nomor telepon
	*/
	function ubah()
	{
		//retrieve data toko yang akan diedit berdasar kode toko
		if(isset($_POST['submit_search_toko']))
		{
			$shop_code = $this->input->post('shop_code');
			$query = $this->db->get_where('shop',array('shop_code'=>$shop_code));
			$row = $query->row();
			$this->data['shop_code'] = $row->shop_code;
			$this->data['shop_name'] = $row->shop_name;
			$this->data['shop_initial'] = $row->shop_initial;
			$this->data['shop_address'] = $row->shop_address;
			$this->data['shop_phone'] = $row->shop_phone;
			$this->data['shop_supervisor'] = $row->shop_supervisor;
		}
		if(isset($_POST['submit_ubah_toko']))
		{
			$shop_code = $this->input->post('shop_code');
			if($this->validate_form_tambah_toko())
			{
				$data = array(
					'shop_address'=>$this->input->post('shop_address'),
					'shop_phone'=>$this->input->post('shop_phone'),
					'shop_supervisor'=>$this->input->post('shop_supervisor')					
				);
				$this->db->where('shop_code',$shop_code);
				if($this->db->update('shop',$data))
				{
					$this->data['notify'] = 'Data toko telah disimpan';
				}
				else
				{
					$this->data['notify'] = 'Data toko gagal disimpan, terjadi error';
				}
			}
		}		
		$this->list_toko();		
		$this->load->view('tok_ubah',$this->data);
	}
}
/* End of file toko.php */
/* Location: ./system/application/controllers/toko.php */