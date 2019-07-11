<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller 
{

	public function __construct () 
	{
		parent::__construct();
		$this->load->model ('Madmin', '', TRUE);
		$this->load->model ('mlogin', '', TRUE);		
		date_default_timezone_set('Asia/Jakarta');				
	}
	
	public function index()
	{
		$this->Globalrules->session_rule();
		redirect('dashboard/home');
	}

	public function change_password()
	{
		# code...
		$this->Globalrules->session_rule();
		$this->Globalrules->notif_message();		
		$data['title']      		= 'Ubah Password';		
		$data['subtitle']   		= '';
		$data['content']    		= 'admin/profile/data_profile';
		$data['infoPegawai']        = $this->Globalrules->get_info_pegawai();
		$data['agama']              = $this->Allcrud->listData('mr_agama')->result_array();
		$data['golongan']           = $this->Allcrud->listData('mr_golongan')->result_array();
		// $data['info_kompetensi']    = $this->Allcrud->getData('mr_kompetensi',array('id_pegawai'=>$this->session->userdata('sesUser')))->result_array();
		$data['history_golongan']   = $this->Globalrules->get_history_golongan();
		$data['history_jabatan']  	= $this->Globalrules->get_history_jabatan();
		$data['history_pendidikan'] = $this->Globalrules->get_history_pendidikan();
		$data['diklat_struktural'] 	= $this->Globalrules->get_history_diklat('1');
		$data['diklat_fungsional'] 	= $this->Globalrules->get_history_diklat('2');
		$data['diklat_teknis'] 		= $this->Globalrules->get_history_diklat('3');
		$data['pendidikan'] 		= $this->Allcrud->listData('mr_pendidikan');
		$data['diklat'] 			= $this->Allcrud->listData('mr_diklat');		
		$this->load->view('templateAdmin',$data);		
	}



	public function redirect_notifikasi($id)
	{
		# code...
		$this->Globalrules->session_rule();		
		$res = $this->Globalrules->get_log_notify_id($id);
		if ($res != 0) {
			# code...
			$data_notify  = array
							(
								'id_table'   => $res[0]->id_table,
								'table_name' => $res[0]->table_name
							);			
			$this->Globalrules->push_notifikasi($data_notify,'read_data');			
			redirect($res[0]->url);
		}
	}

	public function user()
	{
		$this->Globalrules->session_rule();
		$this->Globalrules->notif_message();	

		$data['title']   = 'Administrator';
		$data['content'] = 'admin/user/data_user';
		$data['role']    = $this->Allcrud->listData('user_role');
		$data['user']    = $this->madmin->datauser();
		$this->load->view('templateAdmin',$data);
	}

	public function adduser()
	{
		$this->Globalrules->session_rule();		
		$this->Globalrules->notif_message();		
		$add = array
				(
					'nama'     => modif_kata($this->input->post('nama')),
					'email'    => $this->input->post('email'),
					'password' => sha1(md5('gsi123')),
					'no_hp'    => $this->input->post('hp'),
					'id_role'  => $this->input->post('role')
				);

		$this->Allcrud->addData('user',$add);
	}

	public function ajaxuser(){
		$this->Globalrules->notif_message();		
		$data['user'] = $this->madmin->datauser();
		$this->load->view('admin/user/ajaxuser',$data);
	}

	public function edituser($id){
		$this->Globalrules->notif_message();		
		$flag = array('id_user'=>$id);
		$q    = $this->Allcrud->getData('user',$flag)->row();
		echo json_encode($q);
	}

	public function pedituser(){
		$this->Globalrules->notif_message();		
		$flag = array('id_user'=>$this->input->post('oid'));
		$edit = array(
						'nama'    => modif_kata($this->input->post('nnama')),
						'email'   => $this->input->post('nemail'),
						'no_hp'   => $this->input->post('nhp'),
						'id_role' => $this->input->post('nrole')
					);
		$this->Allcrud->editData('user',$edit,$flag);
	}

	public function deluser($id)
	{
		$this->Globalrules->notif_message();		
		$flag = array('id_user' => $id);
		$this->Allcrud->delData('user',$flag);
	}
	
	public function role()
	{
		$this->Globalrules->session_rule();
		$this->Globalrules->notif_message();				
		$data['title']   = 'role user';
		$data['content'] = 'admin/role/data_role';
		$data['role']    = $this->Allcrud->listData('user_role');
		$this->load->view('templateAdmin',$data);
	}

	public function addrole()
	{
		$this->Globalrules->notif_message();				
		$role = $this->input->post('role');
		$ket  = $this->input->post('ket');
		$add  = array('nama_role' => modif_kata($role),'keterangan'=>$ket);
		$this->Allcrud->addData('user_role',$add);
	}
	
	public function ajaxrole()
	{
		$this->Globalrules->notif_message();				
		$data['content'] = 'admin/role/data_role';
		$data['role']    = $this->Allcrud->listData('user_role');
		$this->load->view('admin/role/ajaxrole',$data);
	}
	
	public function delrole($id)
	{
		$this->Globalrules->notif_message();				
		$flag = array('id_role' => $id);
		$this->Allcrud->delData('user_role',$flag);
	}
	
	public function editrole($id)
	{
		$this->Globalrules->notif_message();				
		$flag = array('id_role'=>$id);
		$q    = $this->Allcrud->getData('user_role',$flag)->row();
		echo json_encode($q);
	}
	
	public function peditrole()
	{
		$this->Globalrules->notif_message();				
		$flag = array('id_role'=>$this->input->post('oid'));
		$edit = array(
			'nama_role'  => $this->input->post('nrole'),
			'keterangan' => $this->input->post('nket')
		);
		$this->Allcrud->editData('user_role',$edit,$flag);
	}

	public function generate($id)
	{
		$this->Globalrules->notif_message();				
		$flag = array('flag' => 1);
		$menu = $this->Allcrud->getData('config_menu',$flag);
		foreach ($menu->result() as $row)
		{
			$cari = $this->Allcrud->getData(
												'config_menu_akses',
												array(
														'id_menu'=>$row->id_menu,
														'id_role'=>$id
													)
											)->num_rows();
			if ($cari == 0)
			{
				$add = array ('id_menu' => $row->id_menu,'id_role' => $id);
				$this->Allcrud->addData('config_menu_akses',$add);
			}
		}
	}


	public function akses($id_role)
	{
		$this->Globalrules->session_rule();
		$this->Globalrules->notif_message();				
		$data['id_role']  = $id_role;
		$data['title']    = 'Hak akses';
		$data['content']  = 'admin/akses/data_akses';
		$data['role']     = $this->Allcrud->listData('user_role');
		$sub              = $this->Allcrud->getData('user_role',array('id_role'=>$id_role))->row();

		if ($sub != "") {
			# code...
			$data['subtitle'] = $sub->nama_role;
		}
		else
		{
			$data['subtitle'] = "";			
		}
		$this->load->view('templateAdmin',$data);
	}

	public function create()
	{
		$this->Globalrules->notif_message();				
		$flag = array ('id_akses' => $this->input->post('id_akses'));
		
		if($this->input->post('nilai') == 0)
		{
			$this->Allcrud->editData(
										'config_menu_akses',
										array('buat'=>1),
										$flag
									);
		}
		else
		{
			$this->Allcrud->editData(
										'config_menu_akses',
										array('buat'=>0),
										$flag
									);
		}
		$data['id_role'] = $this->input->post('role');
		$this->load->view('akses/ajaxAkses',$data);
	}

	public function atasan()
	{
		$this->Globalrules->notif_message();				
		$flag = array ('id_akses' => $this->input->post('id_akses'));
		
		if($this->input->post('nilai') == 0)
		{
			$this->Allcrud->editData(
										'config_menu_akses',
										array('atasan'=>1),
										$flag
									);
		}
		else
		{
			$this->Allcrud->editData(
										'config_menu_akses',
										array('atasan'=>0),
										$flag
									);
		}
		$data['id_role'] = $this->input->post('role');
		$this->load->view('akses/ajaxAkses',$data);
	}
	
	public function bawahan()
	{
		$this->Globalrules->notif_message();				
		$flag = array ('id_akses' => $this->input->post('id_akses'));
		
		if($this->input->post('nilai') == 0)
		{
			$this->Allcrud->editData(
										'config_menu_akses',
										array('bawahan'=>1),
										$flag
									);
		}
		else
		{
			$this->Allcrud->editData(
										'config_menu_akses',
										array('bawahan'=>0),
										$flag
									);
		}
		$data['id_role'] = $this->input->post('role');
		$this->load->view('akses/ajaxAkses',$data);
	}	
	
	public function read(){
		$this->Globalrules->notif_message();				
		$flag = array ('id_akses' => $this->input->post('id_akses'));
		$q    = $this->Allcrud->listData('user_role');

		if($this->input->post('nilai') == 0)
		{
			$this->Allcrud->editData(
										'config_menu_akses',
										array('baca'=>1),
										$flag
									);
		}
		else
		{
			$this->Allcrud->editData('config_menu_akses',array('baca'=>0),$flag);
		}
		$data['id_role'] = $this->input->post('role');
		$this->load->view('akses/ajaxAkses',$data);
		
	}

	public function update()
	{
		$this->Globalrules->notif_message();				
		$flag = array (
						'id_akses' => $this->input->post('id_akses')
					);

		if($this->input->post('nilai') == 0)
		{
			$this->Allcrud->editData(
										'config_menu_akses',
										array('ubah'=>1),
										$flag	
									);
		}
		else
		{
			$this->Allcrud->editData(
										'config_menu_akses',
										array('ubah'=>0),
										$flag
									);
		}
		$data['id_role'] = $this->input->post('role');
		$this->load->view('akses/ajaxAkses',$data);
	}
	
	public function delet()
	{
		$this->Globalrules->notif_message();				
		$flag = array (
						'id_akses' => $this->input->post('id_akses')
					);
		
		if($this->input->post('nilai') == 0)
		{
			$this->Allcrud->editData(
										'config_menu_akses',
										array('hapus'=>1),
										$flag
									);
		}
		else
		{
			$this->Allcrud->editData(
										'config_menu_akses',
										array('hapus'=>0),
										$flag
									);
		}
		$data['id_role'] = $this->input->post('role');
		$this->load->view('akses/ajaxAkses',$data);
	}
	
	public function caries2()
	{
		$this->Globalrules->notif_message();				
		$flag = array('id_es1'=>$this->input->post('es1'));
		$data['es2']= $this->Allcrud->getData('mr_eselon2',$flag);
		$this->load->view('admin/urtug/ajax/eselon2',$data);
	}
	
	public function caries3()
	{
		$this->Globalrules->notif_message();				
		$flag = array('id_es2'=>$this->input->post('es2'));
		$data['es3']= $this->Allcrud->getData('mr_eselon3',$flag);
		$this->load->view('admin/urtug/ajax/eselon3',$data);
	}
	
	public function caries4()
	{
		$this->Globalrules->notif_message();				
		$flag = array('id_es3'=>$this->input->post('es3'));
		$data['es4']= $this->Allcrud->getData('mr_eselon4',$flag);
		$this->load->view('admin/urtug/ajax/eselon4',$data);
	}

	public function carijabatan()
	{
		$this->Globalrules->notif_message();				
		if($this->input->post('es4up') != NULL)
		{
			$flag = array(
							'eselon4'=> $this->input->post('es4up')
						);
		}
		elseif($this->input->post('es3up') != NULL)
		{
			$flag = array(
							'eselon3'=> $this->input->post('es3up'),
							'eselon4'=> 0
						);
		}
		elseif($this->input->post('es2up') != NULL)
		{
			$flag = array(
							'eselon2'=> $this->input->post('es2up'),
							'eselon3'=> 0
							);
		}
		else
		{
			$flag = array(
							'eselon1'=> $this->input->post('es1up'),
							'eselon2'=> 0
							);
		}
		$data['jabatan'] = $this->Allcrud->getData('mr_posisi',$flag);
		$this->load->view('admin/urtug/ajax/ajaxjabatan',$data);
	}
}