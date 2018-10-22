<?php
	$row = $select_eselon_2->result();
	if ($param1 == NULL) {
		# code...
		$param1 = 'master';
	}
?>
<div class="form-group">
	<div class="input-group">
        <span class="input-group-addon"><i class="fa fa-star"></i></span>
        <select name="select_eselon_2" id="select_eselon_2" class="form-control filter_data_eselon">
        	<option value="">Pilih Eselon 2</option>
        	<?php
        		if ($select_eselon_2->result() != "") {
        			# code...

        			for ($i=0; $i < count($select_eselon_2->result()); $i++) { 
        				# code...
        	?>
						<option value="<?php echo $row[$i]->id_es2;?>"><?php echo $row[$i]->nama_eselon2;?></option>
         	<?php
         			}
        		}
        	?>
		</select>
	</div>
	<progress class="progress progress-striped progress-animated" id="prg_progress_bar_es3" style="width: 473px;margin-bottom: 0px;visibility: hidden;" value="0" max="100">
		25%
	</progress>		            		            		            	
</div>
<script>
$(document).ready(function(){
	$("#select_eselon_2").change(function(){
		var select_eselon_1 = $("#select_eselon_1").val();
		var select_eselon_2 = $("#select_eselon_2").val();		
		var select_eselon_3 = '';				
		var select_eselon_4 = '';							 
        $('#select_eselon_3').find('option').remove();    
        $('#select_eselon_3').append($("<option></option>").attr("value", '').text('------------NONE------------')); 	         
        $('#select_eselon_4').find('option').remove();    
        $('#select_eselon_4').append($("<option></option>").attr("value", '').text('------------NONE------------')); 	 		
		$.ajax({
			url :"<?php echo site_url()?>master/data_eselon3/cariEs3_filter/<?=$param;?>/<?=$param1;?>",
			type:"post",
			data:"select_eselon_2="+select_eselon_2,
			beforeSend:function(){
				$("#loadprosess").modal('show');				
				$("#halaman_header").html("");
				$("#halaman_footer").html("");
				$('#example1').dataTable().fnDestroy();	        
				$("#example1 tbody tr").remove();    		
				var newrec  = '<tr">' +
		        					'<td colspan="8" class="text-center">Memuat Data</td>'
		    				   '</tr>';		
		        $('#example1 tbody').append(newrec);    				   				
			},									
			success:function(msg){
				$("#isi_select_eselon_3").html(msg);
				var data_link = {
	        					'data_1' : select_eselon_1,
				                'data_2' : select_eselon_2,
				                'data_3' : select_eselon_3,
				                'data_4' : select_eselon_4							
				}				
				$.ajax({
					url :"<?php echo site_url()?><?=$param1;?>/<?=$param;?>",
					type:"post",
					data: { data_sender : data_link},
					success:function(msg){
						$("#example1 tbody tr").remove();    												
						$("#table_content").html(msg);
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
									"<'row'<'col-sm-5'i><'col-sm-7'p>>",
							"bSort": false						 
							// "dom": '<"top"f>rt'
							// "dom": '<"top"fl>rt<"bottom"ip><"clear">'			
						});
						setTimeout(function(){ 
							$("#loadprosess").modal('hide');								
						}, 1000);									
					}
				})
			}
		})
	})
	
})
	</script>