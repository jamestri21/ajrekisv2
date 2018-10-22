<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct () {
		parent::__construct();
		$this->load->model ('mdashboard', '', TRUE);
		$this->load->model ('laporan/mlaporan', '', TRUE);
		date_default_timezone_set('Asia/Jakarta');
	}

	public function home()
	{
		// print_r($this->session->userdata());die();								
		error_reporting(E_ALL ^ E_WARNING);
		$this->Allcrud->session_rule();
		$this->Allcrud->notif_message();

		$data['title']              = '';
		$data['content']            = 'vdashboard';
		$data['belum_diperiksa']    = $this->Allcrud->getData('tr_capaian_pekerjaan',array('status_pekerjaan'=>0,'id_pegawai'=>$this->session->userdata('sesUser'),'tanggal_selesai LIKE'=>date('Y-m').'%'))->num_rows();
		$data['disetujui']          = $this->Allcrud->getData('tr_capaian_pekerjaan',array('status_pekerjaan'=>1,'id_pegawai'=>$this->session->userdata('sesUser'),'tanggal_selesai LIKE'=>date('Y-m').'%'))->num_rows();
		$data['tolak']              = $this->Allcrud->getData('tr_capaian_pekerjaan',array('status_pekerjaan'=>2,'id_pegawai'=>$this->session->userdata('sesUser'),'tanggal_selesai LIKE'=>date('Y-m').'%'))->num_rows();
		$data['revisi']             = $this->Allcrud->getData('tr_capaian_pekerjaan',array('status_pekerjaan'=>3,'id_pegawai'=>$this->session->userdata('sesUser'),'tanggal_selesai LIKE'=>date('Y-m').'%'))->num_rows();
		$data['agama']              = $this->Allcrud->listData('mr_agama')->result_array();
		$data['golongan']           = $this->Allcrud->listData('mr_golongan')->result_array();
		$data['infoPegawai']        = $this->Globalrules->get_info_pegawai();
		$data['info_kompetensi']    = $this->Allcrud->getData('mr_kompetensi',array('id_pegawai'=>$this->session->userdata('sesUser')))->result_array();
		$data['history_golongan']   = $this->mdashboard->get_history_golongan();
		$data['skp']                = $this->Globalrules->data_summary_skp_pegawai($this->session->userdata('sesUser'));
		$data['data_transaksi']     = $this->mlaporan->get_transact($this->session->userdata('sesUser'),1,date('m'),date('Y'));
		$data['menit_efektif_year'] = $this->mlaporan->get_menit_efektif_year();

		// echo "<pre>";
		// print_r($data['menit_efektif_year']);		
		// // var_dump(scandir('/tmp'));
		// echo "</pre>";
		// // echo '<br>';
		// // echo "<pre>";		
		// // var_dump($_SERVER);
		// // echo "<pre>";
		// die();
		$this->load->view('templateAdmin',$data);
	}

	public function delete_common_notify($param=NULL)
	{
		# code...
		$data_notify = $this->mdashboard->get_data_notify_user($param,$this->session->userdata('sesUser'));
		if (count($data_notify) != 0) {
			# code...
			for ($i=0; $i < count($data_notify); $i++) {
				# code...
				$data_change = array(
					'status_read' => 1
				);
				$flag        = array('id'=>$data_notify[$i]->id);
				$res_data    = $this->Allcrud->editData('log_notifikasi',$data_change,$flag);
			}
		}
	}

	public function load_data_dashboard($stat=NULL)
	{
		# code...
		$id_pegawai   = $this->session->userdata('sesUser');
		$data['list'] = $this->Allcrud->getData('tr_capaian_pekerjaan',array('stat'=>$stat,'id_pegawai'=>$this->session->userdata('sesUser'),'tgl_selesai LIKE'=>date('Y-m').'%'))->result_array();
		$this->load->view('dashboard/ajax_stat_kinerja',$data);
	}

	public function update_profile()
	{
		# code...
		$data_sender = $this->input->post('data_sender');
		$data        = array
						(
							'email'        => $data_sender['email'],
							'no_hp'        => $data_sender['no_hp'],
							'alamat'       => $data_sender['alamat'],
							'id_agama'     => $data_sender['agama'],
							'golongan'     => $data_sender['golongan'],
							'tmt_golongan' => $data_sender['tmt_golongan']
						);
		$flag 		 = array
						(
							'id' => $this->session->userdata('sesUser')
						);
		$res_data       = $this->Allcrud->editData('mr_pegawai',$data,$flag);
		$check_golongan = $this->Allcrud->getData('mr_history_golongan',array('id_golongan'=>$data_sender['golongan'],'id_pegawai'=>$this->session->userdata('sesUser')))->result_array();
		if ($check_golongan == array()) {
			# code...
			$data        = array
							(
								'id_pegawai'  => $this->session->userdata('sesUser'),
								'id_golongan' => $data_sender['golongan'],
								'tmt'         => $data_sender['tmt_golongan']
							);
			$this->Allcrud->addData('mr_history_golongan',$data);			
		}
		$text_status    = $this->Globalrules->check_status_res($res_data,'Profil pegawai telah diubah');
		$res            = array
						(
							'status' => $res_data,
							'text'   => $text_status
						);
		echo json_encode($res);		
	}

	public function update_kompetensi()
	{
		# code...
		$data_sender = $this->input->post('data_sender');
		$data        = array
						(
							'id_pegawai' => $this->session->userdata('sesUser'),
							'kompetensi' => $data_sender['kompetensi'],
							'keterangan' => $data_sender['keterangan']
						);
		$res_data       = $this->Allcrud->addData('mr_kompetensi',$data);
		$text_status    = $this->Globalrules->check_status_res($res_data,'Kompetensi telah ditambahkan.');
		$res            = array
						(
							'status' => $res_data,
							'text'   => $text_status
						);
		echo json_encode($res);				
	}

	public function get_delete_kompetensi($id)
	{
		# code...
		$flag     = array('id' => $id);
		$res_data = $this->Allcrud->delData('mr_kompetensi',$flag);
		$text_status    = $this->Globalrules->check_status_res($res_data,'Kompetensi telah dihapus.');
		$res            = array
						(
							'status' => $res_data,
							'text'   => $text_status
						);
		echo json_encode($res);						
	}	

	public function view_notification(Type $var = null)
	{
		# code...
		$this->Globalrules->session_rule();
		$this->Allcrud->notif_message();
		$data['title']           = 'Lihat Semua Pemberitahuan';
		$data['content']         = 'dashboard/notification/view_all';
		$this->load->view('templateAdmin',$data);
	}




	// public function profil($id)
	// {
	// 	if(!$this->session->userdata('login')){
	// 		redirect('admin/loginadmin');
	// 	}
	// 	$data['pending']      = $this->Allcrud->getData('tr_capaian_pekerjaan',array('stat'=>0,'id_pegawai'=>$this->session->userdata('sesUser'),'tgl_selesai LIKE'=>date('Y-m').'%'))->num_rows();
	// 	$data['terima']       = $this->Allcrud->getData('tr_capaian_pekerjaan',array('stat'=>1,'id_pegawai'=>$this->session->userdata('sesUser'),'tgl_selesai LIKE'=>date('Y-m').'%'))->num_rows();
	// 	$data['tolak']        = $this->Allcrud->getData('tr_capaian_pekerjaan',array('stat'=>2,'id_pegawai'=>$this->session->userdata('sesUser'),'tgl_selesai LIKE'=>date('Y-m').'%'))->num_rows();
	// 	$data['revisi']       = $this->Allcrud->getData('tr_capaian_pekerjaan',array('stat'=>3,'id_pegawai'=>$this->session->userdata('sesUser'),'tgl_selesai LIKE'=>date('Y-m').'%'))->num_rows();
	// 	$data['banding']      = $this->Allcrud->getData('tr_capaian_pekerjaan',array('stat'=>4,'id_pegawai'=>$this->session->userdata('sesUser'),'tgl_selesai LIKE'=>date('Y-m').'%'))->num_rows();
	// 	$data['pegawai']      = $this->mdashboard->dataUser(($id/1988)-1502)->row();
	// 	$data['title']        = 'Profil Pegawai';
	// 	$data['content']      = 'vprofil';
	// 	$data['agama']        = $this->Allcrud->listData('mr_agama');
	// 	$data['status_nikah'] = $this->Allcrud->listData('mr_status_nikah');
	// 	$this->load->view('templateAdmin',$data);
	// }

	// public function setting($id){
	// 	if(!$this->session->userdata('login')){
	// 		redirect('admin/loginadmin');
	// 	}
	// 	$q = $this->mdashboard->dataUser($id)->row();
	// 	echo json_encode($q);
	// }

	// public function editProfil(){
	// 	$pass = $this->input->post('pass');
	// 	$flag = array('id'=>$this->input->post('oid'));
	// 	$lahir = date('Y-m-d',strtotime($this->input->post('lahir')));

	// 	if($pass != NULL || $pass != ""){
	// 		$edit =array(
	// 			'nama_pegawai' => $this->input->post('nama'),
	// 			'tgl_lahir'    => $lahir,
	// 			'alamat'       => $this->input->post('alamat'),
	// 			'gender'       => $this->input->post('gender'),
	// 			'agama'        => $this->input->post('agama'),
	// 			'status_nikah' => $this->input->post('status_nikah'),
	// 			'password'     => sha1(md5($pass))
	// 		);
	// 	}else{
	// 		$edit =array(
	// 			'nama_pegawai' => $this->input->post('nama'),
	// 			'tgl_lahir'    => $lahir,
	// 			'alamat'       => $this->input->post('alamat'),
	// 			'gender'       => $this->input->post('gender'),
	// 			'agama'        => $this->input->post('agama'),
	// 			'status_nikah' => $this->input->post('status_nikah')
	// 		);
	// 	}
	// 	$this->Allcrud->editData('mr_pegawai',$edit,$flag);
	// }

	// public function kinerja(){
	// 	$user = $this->session->userdata('sesUser');
	// 	$data = $this->mdashboard->kinerja($user)->row();
	// 	echo json_encode($data);
	// }
}
