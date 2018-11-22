<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_eselon3 extends CI_Controller {

	public function __construct () {
		parent::__construct();
		$this->load->model ('Mmaster', '', TRUE);
	}
	
	public function index()
	{
		$this->Allcrud->session_rule();						
		$data['title']      = '<b>Struktur Organisasi</b> <i class="fa fa-angle-double-right"></i> Data Eselon 3';
		$data['content']    = 'master/eselon/data_eselon3';
		$data['es1']        = $this->Allcrud->listData('mr_eselon1');
		$data['es2']        = $this->Allcrud->listData('mr_eselon2');
		$data['list']       = $this->Mmaster->eselon3();
		$data['list_final'] = $this->Globalrules->counter_datatable($data['list'],'mr_eselon4','id_es3','id_es3','counter_data');
		$this->load->view('templateAdmin',$data);
	}

	public function ajaxEselon3(){
		$this->Allcrud->session_rule();							
		$data['list']       = $this->Mmaster->eselon3();
		$data['list_final'] = $this->Globalrules->counter_datatable($data['list'],'mr_eselon4','id_es3','id_es3','counter_data');
		$this->load->view('master/eselon/ajaxEselon3',$data);
	}

	public function addEselon3(){
		$this->Allcrud->session_rule();								
		$add = array(
			'id_es2'       => $this->input->post('es2'),
			'nama_eselon3' =>$this->input->post('es3')
		);
		$res_data    = $this->Allcrud->addData('mr_eselon3',$add);
		$text_status = $this->Globalrules->check_status_res($res_data,'Data Eselon 3 telah berhasil ditambahkan.');
		$res         = array
					(
						'status' => $res_data,
						'text'   => $text_status
					);
		echo json_encode($res);												
	}

	public function editEselon3($id){
		$this->Allcrud->session_rule();							
		$flag = array('id_es3'=>$id);
		$q    = $this->Mmaster->getEs3($flag)->row();
		echo json_encode($q);
	}

	public function peditEselon3(){
		$this->Allcrud->session_rule();								
		$flag = array('id_es3'=>$this->input->post('oid'));
		$edit = array(
			'id_es2'       => $this->input->post('nes2'),
			'nama_eselon3' =>$this->input->post('nes3')
		);
		$res_data    = $this->Allcrud->editData('mr_eselon3',$edit,$flag);
		$text_status = $this->Globalrules->check_status_res($res_data,'Data Eselon 3 telah berhasil diubah.');
		$res         = array
					(
						'status' => $res_data,
						'text'   => $text_status
					);
		echo json_encode($res);												
	}

	public function delEselon3($id){
		$this->Allcrud->session_rule();								
		$flag        = array('id_es3' => $id);
		$res_data    = $this->Allcrud->delData('mr_eselon3',$flag);
		$text_status = $this->Globalrules->check_status_res($res_data,'Data Eselon 3 telah berhasil dihapus.');
		$res         = array
					(
						'status' => $res_data,
						'text'   => $text_status
					);
		echo json_encode($res);												
	}

	public function cariEs3(){
		$this->Allcrud->session_rule();								
		$flag        = array('id_es2'=>$this->input->post('es2'));
		$data['es3'] = $this->Allcrud->getData('mr_eselon3',$flag);
		$this->load->view('master/eselon/ajax/eselon3',$data);
	}

	public function cariEs3_filter($param=NULL,$param1=NULL)
	{
		# code...
		$this->Allcrud->session_rule();								
		$flag                    = array('id_es2'=>$this->input->post('select_eselon_2'));
		$data['select_eselon_3'] = $this->Allcrud->getData('mr_eselon3',$flag);
		$data['param']           = $param;
		$data['param1']          = $param1;					
		$this->load->view('master/eselon/ajax/eselon3filter',$data);				
	}				

	public function formEselon3(){
		$this->Allcrud->session_rule();								
		$flag = array('id_es2'=>$this->input->post('nes2'));
		$data['es3']= $this->Allcrud->getData('mr_eselon3',$flag);
		$this->load->view('master/pegawai/eselon3',$data);
	}	
}