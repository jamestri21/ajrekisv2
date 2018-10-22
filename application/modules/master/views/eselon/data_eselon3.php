<div class="col-xs-12">
  	<div class="box">
        <div class="box-header">
			<h3 class  ="box-title pull-right"><button class="btn btn-block btn-primary" id="addData"><i class="fa fa-plus-square"></i> Tambah Eselon 3</button></h3>
			<div class ="box-tools"></div>
        </div>
	    <div class="box-body" id="isi">
	        <table id="example1" class="table table-bordered table-striped">
				<thead>
	                <tr>
						<th>No</th>
						<th>Nama Eselon 1</th>
						<th>Nama Eselon 2</th>
						<th>Nama Eselon 3</th>
						<th>Action</th>
	                </tr>
				</thead>
				<tbody>
					<?php $x=1;
						foreach($list->result() as $row){?>
							<tr>
								<td><?php echo $x;?></td>
								<td><?php echo $row->nama_eselon1;?></td>
								<td><?php echo $row->nama_eselon2;?></td>
								<td><?php echo $row->nama_eselon3;?></td>
								<td><button class="btn btn-primary btn-xs" onclick="edit('<?php echo $row->id_es3;?>')"><i class="fa fa-edit"></i></button>&nbsp;&nbsp;
								<!-- <button class="btn btn-danger btn-xs" onclick="del('<?php echo $row->id_es3;?>')"><i class="fa fa-trash"></i></button> -->
							</tr>
						<?php $x++; }
					?>
				</tbody>
		  </table>
	    </div>
	</div>
</div>


<div class="example-modal">
<div class="modal modal-success fade" id="newData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="box-content">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Form Eselon 3</h4>
              	</div>
                <div class="modal-body" style="background-color: #fff!important;">
					<form id="addForm" name="addForm">
						<label style="color: #000;font-weight: 400;font-size: 19px;">Eselon I</label>
						<div class="form-group">
							<div class="input-group">
			                    <span class="input-group-addon"><i class="fa fa-star"></i></span>
			                    <select name="es1" id="es1" class="form-control"><option value="">Pilih Eselon 1</option>
								<?php foreach($es1->result() as $row){?>
									<option value="<?php echo $row->id_es1;?>"><?php echo $row->nama_eselon1;?></option>
								<?php }?>
								</select>
							</div>
						</div>

						<label style="color: #000;font-weight: 400;font-size: 19px;">Eselon II</label>
						<div id="isies2">
							<div class="form-group">
								<div class="input-group">
				                    <span class="input-group-addon"><i class="fa fa-star"></i></span>
				                    <select name="es2" id="es2" class="form-control"><option value="">Pilih Eselon 2</option></select>
								</div>
							</div>
						</div>

						<label style="color: #000;font-weight: 400;font-size: 19px;">Eselon III</label>
						<div class="form-group">
							<div class="input-group">
		                    	<span class="input-group-addon"><i class="fa fa-star"></i></span>
		                    	<input type="text" id="es3" name="es3" class="form-control" placeholder="Nama Eselon 3" maxlength="100">
							</div>
						</div>

					</form>
                </div>
                <div class="modal-footer" style="background-color: #fff!important;border-top-color: #d2d6de;">
                    <a href="#" class="btn btn-danger" data-dismiss="modal">Keluar</a>
					<input type="submit" class="btn btn-primary" value="Simpan" id="add"/>

                </div>
            </div>
        </div>
	</div>
</div>
</div>

<div class="example-modal">
<div class="modal modal-success fade" id="editData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="box-content">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Form Eselon 3</h4>
                </div>
                <div class="modal-body" style="background-color: #fff!important;">
					<form id="editForm" name="addForm">

						<label style="color: #000;font-weight: 400;font-size: 19px;">Eselon I</label>
						<div class="form-group">
							<div class="input-group">
			                    <span class="input-group-addon"><i class="fa fa-star"></i></span>
			                    <select name="nes1" id="nes1" class="form-control">
								<?php foreach($es1->result() as $row){?>
									<option value="<?php echo $row->id_es1;?>"><?php echo $row->nama_eselon1;?></option>
								<?php }?>
								</select>
							</div>
						</div>

						<label style="color: #000;font-weight: 400;font-size: 19px;">Eselon II</label>
						<div id="nisies2">
							<div class="form-group">
								<div class="input-group">
				                    <span class="input-group-addon"><i class="fa fa-star"></i></span>
				                    <select name="nes2" id="nes2" class="form-control">
									<?php foreach($es2->result() as $row){?>
										<option value="<?php echo $row->id_es2;?>"><?php echo $row->nama_eselon2;?></option>
									<?php }?>
									</select>
								</div>
							</div>
						</div>

						<label style="color: #000;font-weight: 400;font-size: 19px;">Eselon III</label>
						<div class="form-group">
							<div class="input-group">
			                    <span class="input-group-addon"><i class="fa fa-star"></i></span>
			                    <input type="text" id="nes3" name="nes3" class="form-control" placeholder="Nama Eselon 3" maxlength="100">
								<input type="hidden" id="oid" name="oid" >
							</div>
						</div>


					</form>
                </div>
                <div class="modal-footer" style="background-color: #fff!important;border-top-color: #d2d6de;">
                    <a href="#" class="btn btn-danger" data-dismiss="modal">Keluar</a>
					<input type="submit" class="btn btn-primary" value="Simpan" id="edit"/>

                </div>
            </div>
        </div>
	</div>
</div>
</div>

<!-- DataTables -->
<script type='text/javascript' src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script type='text/javascript' src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
$(document).ready(function(){
	$("#addData").click(function(){
		$("#newData").modal('show');
	})

	$("#es1").change(function(){
		var es1 = $("#es1").val();
		$.ajax({
			url :"<?php echo site_url()?>/master/data_eselon2/cariEs2",
			type:"post",
			data:"es1="+es1,
			beforeSend:function(){
				$("#loadprosess").modal('show');
			},
			success:function(msg){
				$("#isies2").html(msg);
				setTimeout(function(){
					$("#loadprosess").modal('hide');
				}, 5000);
			}
		})
	})

	$("#nes1").change(function(){
		var nes1 = $("#nes1").val();
		$.ajax({
			url :"<?php echo site_url()?>/master/data_eselon2/cariEs2edit",
			type:"post",
			data:"nes1="+nes1,
			beforeSend:function(){
				$("#loadprosess").modal('show');
			},
			success:function(msg){
				$("#nisies2").html(msg);
				setTimeout(function(){
					$("#loadprosess").modal('hide');
				}, 5000);
			}
		})
	})

	$("#add").click(function(){
		var es1= $("#es1").val();
		var es2= $("#es2").val();
		var es3= $("#es3").val();

		if (es1.length <= 0)
		{
			Lobibox.alert("warning", //AVAILABLE TYPES: "error", "info", "success", "warning"
			{
				msg: "Data Eselon 1 tidak boleh kosong."
			});
		}
		else if (es2.length <= 0)
		{
			Lobibox.alert("warning", //AVAILABLE TYPES: "error", "info", "success", "warning"
			{
				msg: "Data Eselon 2 tidak boleh kosong."
			});
		}
		else if (es3.length <= 0)
		{
			Lobibox.alert("warning", //AVAILABLE TYPES: "error", "info", "success", "warning"
			{
				msg: "Data Eselon 3 tidak boleh kosong."
			});
		}
		else
		{
			$.ajax({
				url :"<?php echo site_url()?>/master/data_eselon3/addEselon3",
				type:"post",
				data:"es1="+es1+"&es2="+es2+"&es3="+es3,
				beforeSend:function(){
					$("#newData").modal('hide');
					$("#loadprosess").modal('show');
				},
				success:function(){
					Lobibox.notify('success', {
						msg: 'Data Berhasil Ditambahkan'
					});
					$("#isi").load('data_eselon3/ajaxEselon3');
					setTimeout(function(){
						$("#loadprosess").modal('hide');
					}, 5000);
				},
				error:function(){
					Lobibox.notify('error', {
						msg: 'Gagal Melakukan Penambahan data'
					});
				}
			})
		}
	})

	$("#edit").click(function(){
		var es1= $("#nes1").val();
		var es2= $("#nes2").val();
		var es3= $("#nes3").val();

		if (es1.length <= 0)
		{
			Lobibox.alert("warning", //AVAILABLE TYPES: "error", "info", "success", "warning"
			{
				msg: "Data Eselon 1 tidak boleh kosong."
			});
		}
		else if (es2.length <= 0)
		{
			Lobibox.alert("warning", //AVAILABLE TYPES: "error", "info", "success", "warning"
			{
				msg: "Data Eselon 2 tidak boleh kosong."
			});
		}
		else if (es3.length <= 0)
		{
			Lobibox.alert("warning", //AVAILABLE TYPES: "error", "info", "success", "warning"
			{
				msg: "Data Eselon 3 tidak boleh kosong."
			});
		}
		else
		{
			$.ajax({
				url :"<?php echo site_url();?>/master/data_eselon3/peditEselon3",
				type:"post",
				data:$("#editForm").serialize(),
				beforeSend:function(){
					$("#editData").modal('hide');
					$("#loadprosess").modal('show');
				},
				success:function(){
					Lobibox.notify('success', {
						msg: 'Data Berhasil Dirubah'
						});
					$("#isi").load('data_eselon3/ajaxEselon3');
					setTimeout(function(){
						$("#loadprosess").modal('hide');
					}, 5000);
				},
				error:function(){
					Lobibox.notify('error', {
						msg: 'Gagal Melakukan Perubahan data'
					});
				}
			})
		}
	})
})

function edit(id){
	$("#loadprosess").modal('show');
	$.getJSON('<?php echo site_url() ?>/master/data_eselon3/editEselon3/'+id,
		function( response ) {
			$("#editData").modal('show');
			$("#nes1").val(response['id_es1']);
			$("#nes2").val(response['id_es2']);
			$("#nes3").val(response['nama_eselon3']);
			$("#oid").val(response['id_es3']);
			setTimeout(function(){
				$("#loadprosess").modal('hide');
			}, 1000);
		}
	);
}

function del(id){
	 Lobibox.confirm({
		 title: "Konfirmasi",
		 msg: "Anda yakin akan menghapus data ini ?",
		 callback: function ($this, type) {
			if (type === 'yes'){
				$.ajax({
					url :"<?php echo site_url()?>/master/data_eselon3/delEselon3/"+id,
					type:"post",
					beforeSend:function(){
						$("#loadprosess").modal('show');
					},
					success:function(){
						Lobibox.notify('success', {
							msg: 'Data Berhasil Dihapus'
						});
						$("#isi").load('data_eselon3/ajaxEselon3');
						setTimeout(function(){
							$("#loadprosess").modal('hide');
						}, 3000);
					},
					error:function(){
						Lobibox.notify('error', {
							msg: 'Gagal Melakukan Hapus data'
						});
					}
				})
			}
	    }
    })
}

$(function () {
	$("#example1").DataTable({
		"oLanguage": {
			"sSearch": "Pencarian :",
			"sSearchPlaceholder" : "Ketik untuk mencari",
			"sLengthMenu": "Menampilkan data&nbsp; _MENU_ &nbsp;Data",
			"sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
			"sZeroRecords": "Data tidak ditemukan"
		},
		"dom": "<'row'<'col-sm-6'f><'col-sm-6'l>>" +
				"<'row'<'col-sm-5'i><'col-sm-7'p>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-5'i><'col-sm-7'p>>"

		// "dom": '<"top"f>rt'
		// "dom": '<"top"fl>rt<"bottom"ip><"clear">'
	});
});
</script>
