<?php
class Help extends Controller {
	var $data;
	function Help()
	{
        parent::Controller();        
		$this->load->helper(array('html','form','url'));
        $this->data['page_title'] = 'Sistem Inventori Gudang';
		$this->data['pages']='gudang';
        if($this->session->userdata('logged_in')==TRUE)
        {
            $this->load->helper('sisgud');
            $this->data['now_date'] = get_date();
            $this->data['userinfo'] = get_userinfo($this);
        }
        else
        {
            redirect('home/login');
        }
    }
    function about()
    {
        $this->load->view(config_item('template').'help_about',$this->data);
    }
}
//end of file help.php
//location: system/application/controllers