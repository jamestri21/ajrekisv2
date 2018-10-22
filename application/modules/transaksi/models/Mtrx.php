<?php
class Mtrx extends CI_Model
{
 	public function __construct () {
		parent::__construct();
	}

	public function get_pegawai_id($id,$param=NULL)
	{
		# code...
		$sql_1 = "";
		if ($param == 'default') {
			# code...
			$sql_1 = "AND a.id = '".$id."'";
		}
		elseif ($param == 'atasan') {
			# code...
			$sql_1 = "AND b.id = '".$id."'";
		}
		elseif ($param == 'posisi') {
			# code...
			$sql_1 = "AND a.posisi = '".$id."'";
		}
		$sql = "SELECT
					a.nip,
					a.nama_pegawai,
					b.nama_posisi,
					a.es1,
					a.es2,
					a.es3,
					a.es4,
					a.id,
					b.atasan,
					b.id AS `id_posisi`,
					b.kat_posisi,
					c.posisi_class,
					c.tunjangan
				FROM
					mr_pegawai a
				JOIN mr_posisi b ON b.id = a.posisi
				JOIN mr_posisi_class c ON b.posisi_class = c.id
				JOIN mr_eselon1 es1 ON es1.id_es1 = a.es1
				LEFT JOIN mr_eselon2 es2 on es2.id_es2 = a.es2
				LEFT JOIN mr_eselon3 es3 on es3.id_es3 = a.es3
				LEFT JOIN mr_eselon4 es4 on es4.id_es4 = a.es4
				WHERE a. STATUS = '1'
				".$sql_1."";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function status_pekerjaan($status,$id_pegawai)
	{
		# code...
		$sql = "SELECT a.*,
			 		   	b.kegiatan as `uraian_tugas`,
					   	COALESCE(c.nama,'-') as `target_output_name`,
					   	d.kegiatan as `kegiatan_skp`,
						COALESCE(
						(
							SELECT SUM(aa.frekuensi_realisasi)
							FROM tr_capaian_pekerjaan aa
							WHERE aa.id_pegawai = a.id_pegawai
							AND aa.id_uraian_tugas = a.id_uraian_tugas
							AND aa.status_pekerjaan = '1'
						),0) as `realisasi_skp`,
						b.target_qty as `target_skp`
				FROM tr_capaian_pekerjaan a
				JOIN mr_skp_pegawai b ON a.id_uraian_tugas = b.skp_id
				LEFT OUTER JOIN mr_skp_satuan c ON b.target_output = c.id
				LEFT OUTER JOIN mr_skp_master d ON b.id_skp_master = d.id_skp
				WHERE a.tanggal_mulai LIKE '%".date('Y-m')."%'
				AND a.tanggal_selesai LIKE '%".date('Y-m')."%'
				AND a.status_pekerjaan = '".$status."'
				AND a.id_pegawai = '".$id_pegawai."'";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function get_kinerja_anggota($status,$id_posisi,$id_pegawai=NULL)
	{
		# code...
		$sql_1 = "";
		if ($id_pegawai != NULL) {
			# code...
			$sql_1 = "AND a.id_pegawai = '".$id_pegawai."'";
		}

		$sql = "SELECT a.*,
							 b.nama_pegawai,
							 b.nip,
							 d.kegiatan as `uraian_tugas`,
							 d.target_qty,
							 d.target_output,
							 COALESCE(e.nama,'-') as `target_output_name`,
							 f.kegiatan AS `kegiatan_skp`,
							COALESCE(
							(
								SELECT SUM(aa.frekuensi_realisasi)
								FROM tr_capaian_pekerjaan aa
								WHERE aa.id_pegawai = a.id_pegawai
								AND aa.tanggal_mulai LIKE '%2018-08%'
								AND aa.tanggal_selesai LIKE '%2018-08%'
								AND a.status_pekerjaan = '1'
							),0) as `realisasi_skp`,
							d.target_qty as `target_skp`
				FROM tr_capaian_pekerjaan a
				JOIN mr_pegawai b ON a.id_pegawai = b.id
				JOIN mr_posisi c ON b.posisi = c.id
				JOIN mr_skp_pegawai d ON a.id_uraian_tugas = d.skp_id
				LEFT OUTER JOIN mr_skp_satuan e ON d.target_output = e.id
				LEFT OUTER JOIN mr_skp_master f ON d.id_skp_master = f.id_skp
				WHERE a.tanggal_mulai LIKE '%".date('Y-m')."%'
				AND a.tanggal_selesai LIKE '%".date('Y-m')."%'
				AND c.atasan = '".$id_posisi."'
				AND a.status_pekerjaan = '".$status."'
				".$sql_1."";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function get_transaksi_id($id)
	{
		# code...
		$sql = "SELECT a.*,
						COALESCE(c.nama,'-') as `target_output_name`
				FROM tr_capaian_pekerjaan a
				JOIN mr_skp_pegawai b
				ON a.id_uraian_tugas = b.skp_id
				LEFT OUTER JOIN mr_skp_satuan c
				ON b.target_output = c.id
				WHERE a.id_pekerjaan = '".$id."'";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function get_member($posisi)
	{
		# code...
		$sql = "SELECT DISTINCT a.*,
								a.id as `id_pegawai`,
								b.nama_posisi
			    FROM mr_pegawai a
			    JOIN mr_posisi b
			    ON a.posisi = b.id
			    WHERE b.atasan = '$posisi'
			    AND a.status = 1
			    ORDER BY a.nama_pegawai asc";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function get_uraian_tugas($id)
	{
		# code...
		$sql = "SELECT a.*
				FROM mr_uraian_tugas a
				WHERE a.posisi = '".$id."'
				AND a.uraian_tugas LIKE '%menilai%'
				LIMIT 1";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function get_last_data_transact($id)
	{
		# code...
		$sql = "SELECT a.*
				FROM tr_capaian_pekerjaan a
				WHERE a.id_pegawai = '".$id."'
				AND a.status_pekerjaan = '0'
				ORDER BY a.audit_insert DESC
				LIMIT 1";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function get_pegawai_es2($es1,$es2)
	{
		# code...
		$sql = "SELECT a.*,
						 b.nama_posisi,
						 b.kat_posisi,
						 b.atasan,
						 c.posisi_class as grade,
						 c.tunjangan
				FROM mr_pegawai a
				JOIN mr_posisi b
				ON a.posisi = b.id
				JOIN mr_posisi_class c
				ON b.posisi_class = c.id
				WHERE a.es1 = '".$es1."'
				AND a.es2 = '".$es2."'
				AND a.id_role = 4
				AND a.status = 1
				AND c.posisi_class < '17'
				AND c.posisi_class > '13'";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function get_kinerja_banding($id)
	{
		# code...
		$sql = "SELECT a.*,
							b.kegiatan as `uraian_tugas`,
							c.nip,
							c.nama_pegawai
				FROM tr_capaian_pekerjaan a
				JOIN mr_skp_pegawai b ON a.id_uraian_tugas = b.skp_id
				JOIN mr_pegawai c ON a.id_pegawai = c.id
				WHERE a.tanggal_mulai LIKE '%".date('Y-m')."%'
				AND a.tanggal_selesai LIKE '%".date('Y-m')."%'
				AND a.id_pegawai_es2_banding = '".$id."'
				AND a.status_pekerjaan = '6'";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function get_hari_kerja()
	{
		# code...
		$bulan = date('m');
		$tahun = date('Y');
		$sql = "SELECT a.*
				FROM mr_hari_aktif  a
				WHERE a.bulan = '".$bulan."'
				AND a.tahun = '".$tahun."'";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function tugas_tambahan($id_pegawai,$param=NULL,$param1=NULL)
	{
		# code...
		$sql_1 = "";
		if ($param != NULL) {
			# code...
			$sql_1 = "AND a.approve = '".$param."'";
			if ($param == '3') {
				# code...
				$sql_1 = "AND a.approve = '0'";
			}
		}

		$sql_2 = "";
		if ($param1 != NULL) {
			# code...
			if ($param1 == 'tugas-tambahan') {
				# code...
				$sql_2 = "AND a.flag <> 'kreativitas'";
			}
			else
			{
				$sql_2 = "AND a.flag = '".$param1."'";
			}
		}

		// print_r($param1);die();
		$sql = "SELECT a.*,
					   COALESCE(b.pejabat_penanda_tangan,'-') as pejabat_penanda_tangan,
					   b.nilai
				FROM tr_tugas_tambahan_detail a
				LEFT JOIN mr_keterangan_kreativitas b
				ON a.id_keterangan_kreativitas = b.id
				WHERE a.id_pegawai = '".$id_pegawai."'
				".$sql_1."
				".$sql_2."";
				// print_r($sql);die();
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function tugas_tambahan_id($id)
	{
		# code...
		$sql = "SELECT a.*,
					   a.jenis_tugas_tambahan as `id_jenis`,
					   b.jenis_tugas_tambahan,
					   b.nilai
				FROM tr_tugas_tambahan a
				JOIN mr_tugas_tambahan_jenis b
				ON a.jenis_tugas_tambahan = b.id
				WHERE a.id = '".$id."'";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function check_tugas_tambahan($id_pegawai,$tahun)
	{
		# code...
		$sql = "SELECT a.*,
					   b.jenis_tugas_tambahan,
					   b.nilai
				FROM tr_tugas_tambahan a
				JOIN mr_tugas_tambahan_jenis b
				ON a.jenis_tugas_tambahan = b.id
				WHERE a.id_pegawai = '".$id_pegawai."'
				AND a.tahun = '".$tahun."'";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}

	public function tugas_tambahan_detail_id($id)
	{
		# code...
		$sql = "SELECT a.*
				FROM tr_tugas_tambahan_detail a
				WHERE a.id = '".$id."'";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return 0;
		}
	}
}
