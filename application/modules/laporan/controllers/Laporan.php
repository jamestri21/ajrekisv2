<?php defined('BASEPATH') OR exit('No direct script access allowed');
//require_once APPPATH."third_party\PHPExcel.php";
/*
Create by : Bryan Setyawan Putra
Date 	  : 01/07/2016
*/

class Laporan extends CI_Controller {

	public function __construct () {
		parent::__construct();
		$this->load->model ('mlaporan', '', TRUE);
		$this->load->model ('master/Mmaster', '', TRUE);
		$this->load->library('excel');
		$this->load->helper(array('url','form'));
		$this->load->library('image_lib');
		$this->load->library('upload');
	}

	public function index()
	{
		if(!$this->session->userdata('login')){
			redirect('admin/loginadmin');
		}
		$this->Allcrud->notif_message();
		redirect('dashboard/home');
	}

	public function import_tunkir_produktivitas_disiplin()
	{
		# code...
			if(!$this->session->userdata('login')){
			redirect('admin/loginadmin');
		}
		$this->Allcrud->notif_message();
		$data['title']                = 'Import Tunjangan Kinerja Aspek Produktivitas dan Aspek Disiplin';
		$data['subtitle']             = '';
		$data['bulan']				  = $this->Globalrules->data_bulan();
		$data['content']              = 'laporan/import_tunkir_produktivitas_disiplin/import_tunkir_produktivitas_disiplin';
		$this->load->view('templateAdmin',$data);
	}

	public function import_tunkir_produktivitas_disiplin_excel($bulan,$tahun)
	{
		# code...
		$data_store = "";
		if($_FILES['file']['error'] == 4)
		{
			return false;
		}


		$config['upload_path']        = './public/excel/';
		$config['allowed_types']      = 'xls|xlsx';
		$config['max_size']           = 20000;

        $this->load->library('upload');
        $this->upload->initialize($config);

        // print_r($_FILES['userfile']);die();

        if( $this->upload->do_upload('file') )
        {
			//load the excel library
			$this->load->library('excel');

			$dataImage     = $this->upload->data();

			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($dataImage['full_path']);

			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format
			foreach ($cell_collection as $cell) {
				$column     = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
				$row        = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
				$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

			    //header will/should be in row 1 only. of course this can be modified to suit your need.
			    if ($row == 1) {
			        $header[$row][$column] = $data_value;
			    } else
			    {
			    	if ($row == 2) {
			    		# code...
			    	}
			    	elseif ($row == 4) {
			    		# code...
				        $header[$row][$column] = $data_value;
			    	}
			    	else
			    	{
			        	$arr_data[$row][$column] = $data_value;
			    	}
			    }
			}

			//send the data in an array format
			$data['values'] = $arr_data;

			//clean data
			$data_count = (6+count($data['values']));
			for ($i=15; $i <= $data_count; $i++) {
				# code...
				if ($data['values'][$i]['A'] != 'Total') {
					# code...
					$data_store[$i-15]        = array
										(
											'nama'              => $data['values'][$i]['B'],
											'nip'               => $data['values'][$i]['C'],
											'npwp'              => $data['values'][$i]['D'],
											'gol'               => $data['values'][$i]['E'],
											'kelas_jabatan'     => $data['values'][$i]['F'],
											'tunjangan_kinerja' => $data['values'][$i]['G'],
											'tunjangan_plt_plh' => $data['values'][$i]['H'],
											'total_pengurangan' => $data['values'][$i]['I'],
											'total'             => $data['values'][$i]['J']
									);
				}
			}


			$es2 = $this->session->userdata('sesEs2');

			for ($i=0; $i < count($data_store); $i++) {
				# code...
				$data_save        = array
									(
										'es2'				=> $es2,
										'nama'              => $data_store[$i]['nama'],
										'nip'               => $data_store[$i]['nip'],
										'npwp'              => $data_store[$i]['npwp'],
										'gol'               => $data_store[$i]['gol'],
										'kelas_jabatan'     => $data_store[$i]['kelas_jabatan'],
										'tunjangan_kinerja' => $data_store[$i]['tunjangan_kinerja'],
										'tunjangan_plt_plh' => $data_store[$i]['tunjangan_plt_plh'],
										'total_pengurangan' => $data_store[$i]['total_pengurangan'],
										'total'             => $data_store[$i]['total'],
										'bulan'				=> $bulan,
										'tahun'				=> $tahun
								);
				$this->Allcrud->addData('tr_import_tunkir_produktivitas_disiplin_temp',$data_save);
			}
			$res = array
						(
							'status' => 1,
							'text'   => 'Unggah data berhasil'
						);
			echo json_encode($res);

			// $this->show_data_displin($es2,$bulan,$tahun);
        }
        else
        {
            echo $this->upload->display_errors();
        }
	}

	public function show_data_displin($es2,$bulan,$tahun)
	{
		# code...
		$res_data      = 1;
		$data['list']  = $this->mlaporan->import_temporary($es2,$bulan,$tahun);
		$data['state'] = 'temp';
		$this->load->view('laporan/import_tunkir_produktivitas_disiplin/getdat_temp',$data);
	}

	public function check_data_import_produktivitas_tunkir($es2,$bulan,$tahun)
	{
		# code...
		$data['list'] = $this->mlaporan->import_temporary($es2,$bulan,$tahun);
	}

	public function get_import_produktivitas_tunkir($es2,$bulan,$tahun)
	{
		# code...
		$data_sender   = $this->input->post('data_sender');
		$data['state'] = 'temp';
		$data['list']  = $this->mlaporan->import_temporary($es2,$bulan,$tahun);
		$this->load->view('laporan/import_tunkir_produktivitas_disiplin/getdat_temp',$data);
	}

	public function save_import_produktivitas_tunkir($es2,$bulan,$tahun)
	{
		# code...
		$res_data    = "";
		$data_sender = $this->mlaporan->import_temporary($es2,$bulan,$tahun);
		// print_r($data_sender[0]->nama);die();
		for ($i=0; $i < count($data_sender); $i++) {
			# code...
			if ($data_sender[$i]->verify_nip_nama != 'invalid') {
				# code...
				$data_save        = array
									(
										'es2'				=> $es2,
										'nama'              => $data_sender[$i]->nama,
										'nip'               => $data_sender[$i]->nip,
										'npwp'              => $data_sender[$i]->npwp,
										'gol'               => $data_sender[$i]->gol,
										'kelas_jabatan'     => $data_sender[$i]->kelas_jabatan,
										'tunjangan_kinerja' => $data_sender[$i]->tunjangan_kinerja,
										'tunjangan_plt_plh' => $data_sender[$i]->tunjangan_plt_plh,
										'total_pengurangan' => $data_sender[$i]->total_pengurangan,
										'total'             => $data_sender[$i]->total,
										'bulan'				=> $bulan,
										'tahun'				=> $tahun
								);
				$this->Allcrud->addData('tr_import_tunkir_produktivitas_disiplin',$data_save);
				$flag     = array('id' => $data_sender[$i]->id);
				$res_data = $this->Allcrud->delData('tr_import_tunkir_produktivitas_disiplin_temp',$flag);
			}
			else
			{
				$flag     = array('id' => $data_sender[$i]->id);
				$res_data = $this->Allcrud->delData('tr_import_tunkir_produktivitas_disiplin_temp',$flag);
			}
		}

		$text_status = $this->Globalrules->check_status_res($res_data,'Data import telah disimpan.');
		$res = array
					(
						'status' => $res_data,
						'text'   => $text_status
					);
		echo json_encode($res);
	}

	public function get_import_produktivitas_tunkir_real($es2,$bulan,$tahun)
	{
		# code...
		$data['list']  = $this->mlaporan->import_real($es2,$bulan,$tahun);
		$data['state'] = 'real';
		$this->load->view('laporan/import_tunkir_produktivitas_disiplin/getdat_temp',$data);
	}

	public function rekap_kinerja()
	{
		$this->Allcrud->session_rule();
		$this->Allcrud->notif_message();
		$data['title']      = 'Rekapitulasi Data Kinerja';
		$data['content']    = 'laporan/kinerja/data_kinerja';
		$data['bulan_list'] = $this->Allcrud->data_bulan();
		$data['list']       =  '';
		$data['es1']          = $this->Allcrud->listData('mr_eselon1');
		$data['es2']		  = $this->Allcrud->getData('mr_eselon2',array('id_es1'=>$this->session->userdata('sesEs1')));
		$data['role']         = $this->Allcrud->listData('user_role');
		// echo "<pre>";print_r($data['list']);die();
		$this->load->view('templateAdmin',$data);
	}

	public function filter_data_pegawai_rekap_kinerja()
	{
		# code...
		$data_sender = $this->input->post('data_sender');
		$data_sender = array
						(
							'eselon1' => $data_sender['data_1'],
							'eselon2' => $data_sender['data_2'],
							'eselon3' => $data_sender['data_3'],
							'eselon4' => $data_sender['data_4']
						);

		$data = $this->get_data_summary($data_sender);
		$this->load->view('laporan/kinerja/ajax_kinerja',$data);
	}

	public function filter_data_pegawai_rekap_kinerja_prod_disiplin()
	{
		# code...
		$data_sender = $this->input->post('data_sender');
		$data_sender = array
						(
							'eselon1' => $data_sender['data_1'],
							'eselon2' => $data_sender['data_2'],
							'eselon3' => $data_sender['data_3'],
							'eselon4' => $data_sender['data_4']
						);

		$data = $this->get_data_summary($data_sender);
		$this->load->view('laporan/tunkir_produktivitas_disiplin/ajax_tunkir_produktivitas_disiplin',$data);
	}

	public function get_data_summary($data_sender)
	{
		# code...
		$data['list'] = $this->Mmaster->data_pegawai($data_sender,'a.es2 ASC,
																		a.es3 ASC,
																		a.es4 ASC,
																		b.kat_posisi asc,
																		b.atasan ASC');

		// print_r($this->Globalrules->list_atasan($data['list'][0]->id_posisi));die();
		if ($data['list'] != 0) {
			# code...
			for ($i=0; $i < count($data['list']); $i++) {
				# code...
				$data_atasan                                = $this->Globalrules->list_atasan($data['list'][$i]->id_posisi);
				$data_transaksi                             = $this->mlaporan->get_transact($data['list'][$i]->id_pegawai,1,date('m'),date('Y'));
				$data_belum_diperiksa                       = $this->mlaporan->get_transact($data['list'][$i]->id_pegawai,0,date('m'),date('Y'));
				$data_tolak                                 = $this->mlaporan->get_transact($data['list'][$i]->id_pegawai,2,date('m'),date('Y'));
				$data_revisi                                = $this->mlaporan->get_transact($data['list'][$i]->id_pegawai,3,date('m'),date('Y'));
				if ($data_atasan != 0) {
					// code...
					$data['list'][$i]->nama_atasan	= $data_atasan[0]->nama_pegawai;
				}
				else {
					// code...
					$data['list'][$i]->nama_atasan  = '';
				}
				$data['list'][$i]->disetujui                = $data_transaksi[0]->count_status_pekerjaan;
				$data['list'][$i]->ditolak                  = $data_tolak[0]->count_status_pekerjaan;
				$data['list'][$i]->revisi                   = $data_revisi[0]->count_status_pekerjaan;
				$data['list'][$i]->belum_diperiksa          = $data_transaksi[0]->count_status_pekerjaan;
				$data['list'][$i]->menit_efektif            = $data_transaksi[0]->menit_efektif;
				$data['list'][$i]->menit_efektif_efektif    = $data_transaksi[0]->menit_efektif_sistem;
				$data['list'][$i]->prosentase               = $data_transaksi[0]->prosentase;
				$data['list'][$i]->tugas_belajar            = $data_transaksi[0]->tugas_belajar;


				$data['list'][$i]->tunjangan_kinerja        = $data_transaksi[0]->tunjangan_kinerja;
				$data['list'][$i]->tunjangan_kinerja_sistem = $data_transaksi[0]->tunjangan_kinerja_sistem;
				$data['list'][$i]->tunjangan_disiplin       = $data_transaksi[0]->tunjangan_disiplin;
				$data['list'][$i]->tunjangan_profesi        = $data_transaksi[0]->tunjangan_profesi;
/*************************************************************************************************************************/
			}
		}

		return $data;
	}

	public function rekap_tunkir_produktivitas_disiplin()
	{
		# code...
		$this->Allcrud->session_rule();
		$this->Allcrud->notif_message();
		$data['title']      = 'Rekapitulasi Tunjangan Kinerja Aspek Produktivitas & Aspek Disiplin';
		$data['content']    = 'laporan/tunkir_produktivitas_disiplin/data_tunkir';
		$data['bulan_list'] = $this->Allcrud->data_bulan();
		$data['list']       =  '';
		$data['es1']        = $this->Allcrud->listData('mr_eselon1');
		$data['es2']        = $this->Allcrud->getData('mr_eselon2',array('id_es1'=>$this->session->userdata('sesEs1')));
		$data['role']       = $this->Allcrud->listData('user_role');
		$this->load->view('templateAdmin',$data);
	}









/*
Create by : Bryan Setyawan Putra
Last edit : 26/07/2016

	public function get_data_report(
										$flag            =NULL,
										$bulan           =NULL,
										$tahun           =NULL,
										$eselon1         =NULL,
										$eselon2         =NULL,
										$eselon3         =NULL,
										$eselon4         =NULL,
										$format_date_sql =NULL
									)
	{
		# code...
		$this->Allcrud->notif_message();
		$data         = $this->mlaporan->rekap_kinerja(
														$flag,
														$bulan,
														$tahun,
														$eselon1,
														$eselon2,
														$eselon3,
														$eselon4
													  );

		if ($data != 0) {
			# code...
			for($i = 0; $i < count($data); $i++)
			{
				$temp                  = $this->mlaporan->rekap_kinerja_wrap($data[$i]->id_pegawai);
				$temp_disetujui        = $this->mlaporan->rekap_kinerja_stat($data[$i]->id_pegawai,'1');
				$temp_ditolak          = $this->mlaporan->rekap_kinerja_stat($data[$i]->id_pegawai,'2');
				$temp_revisi           = $this->mlaporan->rekap_kinerja_stat($data[$i]->id_pegawai,'3');
				$temp_belum_diperiksa  = $this->mlaporan->rekap_kinerja_stat($data[$i]->id_pegawai,'0');

				$temp_banding          = $this->mlaporan->rekap_kinerja_stat($data[$i]->id_pegawai,'6');
				$temp_banding_ditolak  = $this->mlaporan->rekap_kinerja_stat($data[$i]->id_pegawai,'7');
				$temp_banding_diterima = $this->mlaporan->rekap_kinerja_stat($data[$i]->id_pegawai,'8');

				if ($temp != 0) {
					# code...
					$data[$i]->jam_kerja        = ceil($temp[0]->jam_kerja);
					$data[$i]->menit_efektif    = ceil($temp[0]->jam_kerja / 6600);
					$data[$i]->tunjangan        = ceil($temp[0]->jam_kerja / 6600 * 0.5 * $data[$i]->tunjangan);
					$data[$i]->tgl_mulai        = $temp[0]->tgl_mulai;
					$data[$i]->jam_mulai        = $temp[0]->jam_mulai;
					$data[$i]->jam_selesai      = $temp[0]->jam_selesai;
					$data[$i]->nama_pekerjaan   = $temp[0]->nama_pekerjaan;
					$data[$i]->output_pekerjaan = $temp[0]->output_pekerjaan;
				}
				else
				{
					$data[$i]->jam_kerja        = "0";
					$data[$i]->menit_efektif    = "0";
					$data[$i]->tunjangan        = "0";
					$data[$i]->tgl_mulai        = "-";
					$data[$i]->jam_mulai        = "-";
					$data[$i]->jam_selesai      = "-";
					$data[$i]->nama_pekerjaan   = "-";
					$data[$i]->output_pekerjaan = "-";
				}

				if ($temp_disetujui != 0) {
					# code...
					$data[$i]->kerja_disetujui     = $temp_disetujui[0]->data_stat;
				}
				else $data[$i]->kerja_disetujui     = "0";

				if ($temp_ditolak != 0) {
					# code...
					$data[$i]->kerja_ditolak     = $temp_ditolak[0]->data_stat;
				}
				else $data[$i]->kerja_ditolak     = "0";

				if ($temp_revisi != 0) {
					# code...
					$data[$i]->kerja_revisi     = $temp_revisi[0]->data_stat;
				}
				else $data[$i]->kerja_revisi     = "0";

				if ($temp_belum_diperiksa != 0) {
					# code...
					$data[$i]->kerja_belum_diperiksa     = $temp_belum_diperiksa[0]->data_stat;
				}
				else $data[$i]->kerja_belum_diperiksa     = "0";

				if ($temp_banding != 0) {
					# code...
					$data[$i]->banding     = $temp_banding[0]->data_stat;
				}
				else $data[$i]->banding     = "0";

				if ($temp_banding_ditolak != 0) {
					# code...
					$data[$i]->banding_ditolak     = $temp_banding_ditolak[0]->data_stat;
				}
				else $data[$i]->banding_ditolak     = "0";

				if ($temp_banding_diterima != 0) {
					# code...
					$data[$i]->banding_disetujui     = $temp_banding_diterima[0]->data_stat;
				}
				else $data[$i]->banding_disetujui     = "0";



			}
		}

		return $data;
	}

/*
Create by : Bryan Setyawan Putra
Last edit : 27/07/2016
*/
	public function get_data_eselon($flag=NULL,$type=NULL,$value=NULL)
	{
		# code...
		$data = "";
		$this->Allcrud->notif_message();
		if ($flag != "ajax") {
			# code...
			$data['data_eselon1']         = $this->mlaporan->get_eselon('1');
			$data['data_eselon2']         = $this->mlaporan->get_eselon('2');
			$data['data_eselon3']         = $this->mlaporan->get_eselon('3');
			$data['data_eselon4']         = $this->mlaporan->get_eselon('4');
		}
		else
		{
			$data = $this->mlaporan->get_eselon($type,'ajax',$value);
		}

		return $data;
	}

/*
Create by : Bryan Setyawan Putra
Last edit : 22/07/2016
*/
//		print_r($data['eselon']['data_eselon1']);die();
	public function rekap_banding()
	{
		$this->Allcrud->session_rule();
		$this->Allcrud->notif_message();
		$flag               = array();
		$data['eselon']     = $this->get_data_eselon();
		$data['title']      = 'Rekapitulasi Data Banding';
		$data['content']    = 'laporan/banding/data_banding';
		$data['bulan_list'] = $this->Allcrud->data_bulan();
		$data['list']       = $this->get_data_report();
		$this->load->view('templateAdmin',$data);
	}

/*
Create by : Bryan Setyawan Putra
Last edit : 13/07/2016
*/
	public function rekap_tunjangan()
	{
		$this->Allcrud->session_rule();
		$this->Allcrud->notif_message();
		$flag               = array();
		$data['eselon']     = $this->get_data_eselon();
		$data['title']      = 'Rekapitulasi Tunjangan Kinerja';
		$data['content']    = 'laporan/tunjangan/data_tunjangan';
		$data['bulan_list'] = $this->Allcrud->data_bulan();
		$data['list']    = $this->get_data_report();
		$this->load->view('templateAdmin',$data);
	}

/*
Create by : Bryan Setyawan Putra
Last edit : 13/07/2016
*/
	public function rekap_keberatan()
	{
		$this->Allcrud->session_rule();
		$this->Allcrud->notif_message();
		$flag               = array();
		$data['eselon']     = $this->get_data_eselon();
		$data['title']      = 'Rekapitulasi Keberatan';
		$data['content']    = 'laporan/keberatan/data_keberatan';
		$data['bulan_list'] = $this->Allcrud->data_bulan();
		$data['list']       = $this->get_data_report();
		$this->load->view('templateAdmin',$data);
	}

/*
Create by : Bryan Setyawan Putra
Last edit : 13/07/2016
*/
	public function preview_data_report()
	{
		$this->Allcrud->notif_message();
		$data_sender     = $this->input->post('data_sender');
		$format_date_sql = $data_sender['tahun']."-".$data_sender['bulan']."-01";

		$eksport = "preview";
		$res     = $this->get_data_report(
											$eksport,
											$data_sender['bulan'],
											$data_sender['tahun'],
											$data_sender['eselon1'],
											$data_sender['eselon2'],
											$data_sender['eselon3'],
											$data_sender['eselon4'],
											$format_date_sql
										);
		echo json_encode($res);
	}

/*
Create by : Bryan Setyawan Putra
Last edit : 28/07/2016
*/
	public function get_data_eselon_by()
	{
		# code...
		$this->Allcrud->notif_message();
		$data_sender = $this->input->post('data_sender');
		$type        = $this->input->post('type');

		$res = $this->get_data_eselon('ajax',$type,$data_sender);

		echo json_encode($res);
	}

/*
Create by : Bryan Setyawan Putra
Last edit : 27/07/2016
*/
	public function export_data_kinerja(
											$bulan   =NULL,
											$tahun   =NULL,
											$eselon1 =NULL,
											$eselon2 =NULL,
											$eselon3 =NULL,
											$eselon4 =NULL
										)
	{
		$this->Allcrud->notif_message();

		if ($bulan == "-")$bulan = "";
		if ($tahun == "-")$tahun = "";
		if ($eselon1 == "-")$eselon1 = "";
		if ($eselon2 == "-")$eselon2 = "";
		if ($eselon3 == "-")$eselon3 = "";
		if ($eselon4 == "-")$eselon4 = "";

		$text_header = "";
		$nama_bulan = $this->Allcrud->set_bulan($bulan);
		if ($bulan == NULL || $tahun == NULL) {
			# code...
			$tahun       = "0000";
			$bulan       = "00";
			$tanggal     = "00";
			$text_header = "Laporan Rekapitulasi Data Kinerja";
		}
		else
		{
			$tanggal = "01";
			$text_header = "Laporan Rekapitulasi Data Kinerja Bulan ".$nama_bulan." Tahun ".$tahun;
		}

		$format_date_sql = $tahun."-".$bulan."-".$tanggal;

		$eksport = "this_is_export";

		$data['list']    = $this->get_data_report(
													$eksport,
													$bulan,
													$tahun,
													$eselon1,
													$eselon2,
													$eselon3,
													$eselon4,
													$format_date_sql
												);
		# code...
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('Rekapitulasi Data Kinerja');

		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(27);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(27);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(11);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(17);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);

		$this->excel->getActiveSheet()->getStyle('B8:L8')->getFont()->setSize(13);
		$this->excel->getActiveSheet()->getStyle('B8:L8')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B8:L8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B8:L8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B8:L8')->getAlignment()->setWraptext(true);
		$this->excel->getActiveSheet()->getStyle('B8:L8')->getBorders()->getallborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$this->excel->getActiveSheet()->mergeCells('B3:L3');
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(22);
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->setCellValue('B3', $text_header);

		$this->excel->getActiveSheet()->setCellValue('B8', 'No');
		$this->excel->getActiveSheet()->setCellValue('C8', 'Nama');
		$this->excel->getActiveSheet()->setCellValue('D8', 'NIP');
		$this->excel->getActiveSheet()->setCellValue('E8', 'Jabatan');
		$this->excel->getActiveSheet()->setCellValue('F8', 'Disetujui');
		$this->excel->getActiveSheet()->setCellValue('G8', 'Ditolak');
		$this->excel->getActiveSheet()->setCellValue('H8', 'Revisi');
		$this->excel->getActiveSheet()->setCellValue('I8', 'Belum Diperiksa');
		$this->excel->getActiveSheet()->setCellValue('J8', 'Menit Kerja Efektif');
		$this->excel->getActiveSheet()->setCellValue('K8', '% Realisasi Kerja Efektif');
		$this->excel->getActiveSheet()->setCellValue('L8', 'Tunjangan');

		$data_count = 9 + count($data['list']);
		for ($i=9; $i < $data_count; $i++) {
			# code...
			$x = 0;
			$counter_i = $i - 9;
			$row_b = "B".$i;
			$row_c = "C".$i;
			$row_d = "D".$i;
			$row_e = "E".$i;
			$row_f = "F".$i;
			$row_g = "G".$i;
			$row_h = "H".$i;
			$row_i = "I".$i;
			$row_j = "J".$i;
			$row_k = "K".$i;
			$row_l = "L".$i;

			$aa = 9;
			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_)->getBorders()->getallborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_l)->getAlignment()->setWraptext(true);
			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_l)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle($row_d)->getNumberFormat()->setFormatCode('0000');

			$this->excel->getActiveSheet()->setCellValue($row_b, $counter_i+1);
			$this->excel->getActiveSheet()->setCellValue($row_c, $data['list'][$counter_i]->nama_pegawai);
			$this->excel->getActiveSheet()->setCellValue($row_d, $data['list'][$counter_i]->nip);
			$this->excel->getActiveSheet()->setCellValue($row_e, $data['list'][$counter_i]->nama_posisi);
			$this->excel->getActiveSheet()->setCellValue($row_f, number_format($data['list'][$counter_i]->kerja_disetujui));
			$this->excel->getActiveSheet()->setCellValue($row_g, number_format($data['list'][$counter_i]->kerja_ditolak));
			$this->excel->getActiveSheet()->setCellValue($row_h, number_format($data['list'][$counter_i]->kerja_revisi));
			$this->excel->getActiveSheet()->setCellValue($row_i, number_format($data['list'][$counter_i]->kerja_belum_diperiksa));
			$this->excel->getActiveSheet()->setCellValue($row_j, number_format($data['list'][$counter_i]->jam_kerja));
			$this->excel->getActiveSheet()->setCellValue($row_k, number_format($data['list'][$counter_i]->menit_efektif));
			$this->excel->getActiveSheet()->setCellValue($row_l, "Rp. ".number_format($data['list'][$counter_i]->tunjangan));
			$x++;
		}

		$filename=' Laporan rekapitulasi data kinerja - '.date("d-m-Y").'.xlsx'; //save our workbook as this file name
		//header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type excel 2007
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
		exit();
		redirect('laporan/rekap_kinerja', false);
	}

/*
Create by : Bryan Setyawan Putra
Last edit : 25/07/2016
*/
	public function export_data_tunjangan(
											$bulan   =NULL,
											$tahun   =NULL,
											$eselon1 =NULL,
											$eselon2 =NULL,
											$eselon3 =NULL,
											$eselon4 =NULL
										)
	{
		# code...
		$this->Allcrud->notif_message();
		if ($bulan == "-")$bulan = "";
		if ($tahun == "-")$tahun = "";
		if ($eselon1 == "-")$eselon1 = "";
		if ($eselon2 == "-")$eselon2 = "";
		if ($eselon3 == "-")$eselon3 = "";
		if ($eselon4 == "-")$eselon4 = "";

		$text_header = "";
		$nama_bulan = $this->Allcrud->set_bulan($bulan);

		if ($bulan == NULL || $tahun == NULL) {
			# code...
			$tahun       = "0000";
			$bulan       = "00";
			$tanggal     = "00";
			$text_header = "Laporan Rekapitulasi Tunjangan Kinerja";
		}
		else
		{
			$tanggal = "01";
			$text_header = "Laporan Rekapitulasi Tunjangan Kinerja Bulan ".$nama_bulan." Tahun ".$tahun;
		}

		$format_date_sql = $tahun."-".$bulan."-".$tanggal;
		$eksport         = "this_is_export";

		$data['list']    = $this->get_data_report(
													$eksport,
													$bulan,
													$tahun,
													$eselon1,
													$eselon2,
													$eselon3,
													$eselon4,
													$format_date_sql
												);

		# code...
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('Rekapitulasi Tunjangan Kinerja');

		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(27);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(27);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(16);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);

		$this->excel->getActiveSheet()->getStyle('B8:J8')->getFont()->setSize(13);
		$this->excel->getActiveSheet()->getStyle('B8:J8')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B8:J8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B8:J8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B8:J8')->getAlignment()->setWraptext(true);
		$this->excel->getActiveSheet()->getStyle('B8:J8')->getBorders()->getallborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//		print_r($this->excel->getActiveSheet());die();

		$this->excel->getActiveSheet()->mergeCells('B3:J3');
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(22);
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->setCellValue('B3', $text_header);

		$this->excel->getActiveSheet()->setCellValue('B8', 'No');
		$this->excel->getActiveSheet()->setCellValue('C8', 'Nama');
		$this->excel->getActiveSheet()->setCellValue('D8', 'NIP');
		$this->excel->getActiveSheet()->setCellValue('E8', 'Jabatan');
		$this->excel->getActiveSheet()->setCellValue('F8', 'Kelas Jabatan');
		$this->excel->getActiveSheet()->setCellValue('G8', '50% Nilai per Grade');
		$this->excel->getActiveSheet()->setCellValue('H8', 'Menit Kerja Efektif (Bulan)');
		$this->excel->getActiveSheet()->setCellValue('I8', 'Realisasi Kerja Efektif');
		$this->excel->getActiveSheet()->setCellValue('J8', 'Jumlah Yang Diterima');

		$data_count = 9 + count($data['list']);
		for ($i=9; $i < $data_count; $i++) {
			# code...
			$x = 0;
			$counter_i = $i - 9;
			$row_b = "B".$i;
			$row_c = "C".$i;
			$row_d = "D".$i;
			$row_e = "E".$i;
			$row_f = "F".$i;
			$row_g = "G".$i;
			$row_h = "H".$i;
			$row_i = "I".$i;
			$row_j = "J".$i;
			$row_k = "K".$i;
			$row_l = "L".$i;

			$aa = 9;
			$realisasi = $data['list'][$counter_i]->menit_efektif / 6600;

			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_j)->getBorders()->getallborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_j)->getAlignment()->setWraptext(true);
			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle($row_d)->getNumberFormat()->setFormatCode('0000');
			$this->excel->getActiveSheet()->getStyle($row_k)->getNumberFormat()->setFormatCode('0.00');

			$this->excel->getActiveSheet()->setCellValue($row_b, $counter_i+1);
			$this->excel->getActiveSheet()->setCellValue($row_c, $data['list'][$counter_i]->nama_pegawai);
			$this->excel->getActiveSheet()->setCellValue($row_d, $data['list'][$counter_i]->nip);
			$this->excel->getActiveSheet()->setCellValue($row_e, $data['list'][$counter_i]->nama_posisi);
			$this->excel->getActiveSheet()->setCellValue($row_f, $data['list'][$counter_i]->posisi_class);
			$this->excel->getActiveSheet()->setCellValue($row_g, "Rp. ".number_format($data['list'][$counter_i]->tunjangan_posisi));
			$this->excel->getActiveSheet()->setCellValue($row_h, number_format(6600));
			$this->excel->getActiveSheet()->setCellValue($row_i, number_format($realisasi));
			$this->excel->getActiveSheet()->setCellValue($row_j, "Rp. ".number_format($data['list'][$counter_i]->tunjangan));
			$x++;
		}

		$filename=' Laporan rekapitulasi tunjangan kinerja - '.date("d-m-Y").'.xlsx'; //save our workbook as this file name
		//header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type excel 2007
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
		exit();
		redirect('laporan/rekap_tunjangan', false);
	}

/*
Create by : Bryan Setyawan Putra
Last edit : 26/07/2016
*/
	public function export_keberatan(
											$bulan   =NULL,
											$tahun   =NULL,
											$eselon1 =NULL,
											$eselon2 =NULL,
											$eselon3 =NULL,
											$eselon4 =NULL
									)
	{
		# code...
		$this->Allcrud->notif_message();
		if ($bulan == "-")$bulan = "";
		if ($tahun == "-")$tahun = "";
		if ($eselon1 == "-")$eselon1 = "";
		if ($eselon2 == "-")$eselon2 = "";
		if ($eselon3 == "-")$eselon3 = "";
		if ($eselon4 == "-")$eselon4 = "";

		$text_header = "";
		$nama_bulan = $this->Allcrud->set_bulan($bulan);
		if ($bulan == NULL || $tahun == NULL) {
			# code...
			$tahun       = "0000";
			$bulan       = "00";
			$tanggal     = "00";
			$text_header = "Laporan Rekapitulasi Data Keberatan";
		}
		else
		{
			$tanggal = "01";
			$text_header = "Laporan Rekapitulasi Data Keberatan Bulan ".$nama_bulan." Tahun ".$tahun;
		}

		$format_date_sql = $tahun."-".$bulan."-".$tanggal;
		$eksport         = "this_is_export";

		$data['list']    = $this->get_data_report(
													$eksport,
													$bulan,
													$tahun,
													$eselon1,
													$eselon2,
													$eselon3,
													$eselon4,
													$format_date_sql
												);
		# code...
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('Rekapitulasi Data Keberatan');

		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(27);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(27);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(11);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(17);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);

		$this->excel->getActiveSheet()->getStyle('B8:M8')->getFont()->setSize(13);
		$this->excel->getActiveSheet()->getStyle('B8:M8')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B8:M8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B8:M8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B8:M8')->getAlignment()->setWraptext(true);
		$this->excel->getActiveSheet()->getStyle('B8:M8')->getBorders()->getallborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$this->excel->getActiveSheet()->mergeCells('B3:M3');
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(22);
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->setCellValue('B3', $text_header);

		$this->excel->getActiveSheet()->setCellValue('B8', 'No');
		$this->excel->getActiveSheet()->setCellValue('C8', 'Nama');
		$this->excel->getActiveSheet()->setCellValue('D8', 'NIP');
		$this->excel->getActiveSheet()->setCellValue('E8', 'Jabatan');
		$this->excel->getActiveSheet()->setCellValue('F8', 'Tanggal');
		$this->excel->getActiveSheet()->setCellValue('G8', 'Jam Mulai');
		$this->excel->getActiveSheet()->setCellValue('H8', 'Jam Selesai');
		$this->excel->getActiveSheet()->setCellValue('I8', 'Pekerjaan');
		$this->excel->getActiveSheet()->setCellValue('J8', 'Detail Pekerjaan');
		$this->excel->getActiveSheet()->setCellValue('K8', 'Output');
		$this->excel->getActiveSheet()->setCellValue('L8', 'File Pendukung');
		$this->excel->getActiveSheet()->setCellValue('M8', 'Status');

		$data_count = 9 + count($data['list']);
		for ($i=9; $i < $data_count; $i++) {
			# code...
			$x = 0;
			$counter_i = $i - 9;
			$row_b = "B".$i;
			$row_c = "C".$i;
			$row_d = "D".$i;
			$row_e = "E".$i;
			$row_f = "F".$i;
			$row_g = "G".$i;
			$row_h = "H".$i;
			$row_i = "I".$i;
			$row_j = "J".$i;
			$row_k = "K".$i;
			$row_l = "L".$i;
			$row_m = "M".$i;

			$aa = 9;
			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_m)->getBorders()->getallborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_m)->getAlignment()->setWraptext(true);
			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle($row_b.':'.$row_m)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle($row_d)->getNumberFormat()->setFormatCode('0000');

			$this->excel->getActiveSheet()->setCellValue($row_b, $counter_i+1);
			$this->excel->getActiveSheet()->setCellValue($row_c, $data['list'][$counter_i]->nama_pegawai);
			$this->excel->getActiveSheet()->setCellValue($row_d, $data['list'][$counter_i]->nip);
			$this->excel->getActiveSheet()->setCellValue($row_e, $data['list'][$counter_i]->nama_posisi);
			$this->excel->getActiveSheet()->setCellValue($row_f, $data['list'][$counter_i]->tgl_mulai);
			$this->excel->getActiveSheet()->setCellValue($row_g, $data['list'][$counter_i]->jam_mulai);
			$this->excel->getActiveSheet()->setCellValue($row_h, $data['list'][$counter_i]->jam_selesai);
			$this->excel->getActiveSheet()->setCellValue($row_i, $data['list'][$counter_i]->nama_pekerjaan);
			$this->excel->getActiveSheet()->setCellValue($row_j, "-");
			$this->excel->getActiveSheet()->setCellValue($row_k, $data['list'][$counter_i]->output_pekerjaan);
			$this->excel->getActiveSheet()->setCellValue($row_l, "-");
			$this->excel->getActiveSheet()->setCellValue($row_m, "-");
			$x++;
		}
		$filename=' Laporan rekapitulasi data keberatan - '.date("d-m-Y").'.xlsx'; //save our workbook as this file name
		//header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type excel 2007
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
		exit();
		redirect('laporan/rekap_keberatan', false);
	}
}
