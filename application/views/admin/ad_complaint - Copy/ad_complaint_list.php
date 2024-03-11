<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo base_url('assets/dist-assets/css/plugins/datatables.min.css') ?>" />
<link href="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet" type="text/css">

    
<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">All Complaints</h1></div>
	<div class="float-right">
		<a class="btn btn-primary export" type="button" href="<?php echo site_url('complaint/export'); ?>">Export</a>

		<?php if($this->role == 'admin') { ?>
			<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('complaint/create'); ?>"><i class="i-Add-File"></i> Add</a>
		<?php } ?>
		
	</div>
	<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>


<div class="row mb-12">
	
<div class="col-md-12">
<p>Search complaints</p>
</div>
<!-- <div class="col-md-3 mb-3"><input class="form-control" id=" " type="text" placeholder="Company Name"></div> -->
<div class="col-md-3 mb-3"><input class="form-control" id="ga_no" type="text" placeholder="GA Number"></div>
<div class="col-md-3 mb-3"><input class="form-control" id="ticket_no" type="text" placeholder="Ticket No."></div>
<!-- <div class="col-md-3 mb-3"><input class="form-control" id="company" type="text" placeholder="Company Name"></div> -->
<div class="col-md-3 mb-3">
	<!-- <input class="form-control" id="complaint_type" type="text" placeholder="Slect Complaint Type"> -->
 	<select class="form-control" id="complaint_type" >
    	<option value="">Select Complaint Type</option>
        <?php
        $complaint_types = complaint_types();
        	foreach ($complaint_types as $key => $value) {
        		echo '<option value="'.$value.'">'.$value.'</option>';
        	}
        ?>
    </select>
</div>

<div class="col-md-3 mb-3">
	<select class="form-control" id="status">
		<option value="">Select Status</option>
		<?php
		$url_status = (isset($_GET['status'])) ? $_GET['status'] :'';
        $status_list = complaint_status_list();
        	foreach ($status_list as $key => $value) {
        		if($key == $url_status && $url_status != ''){
        			echo '<option value="'.$key.'" selected>'.$value.'</option>';
        		}else{
	        		echo '<option value="'.$key.'">'.$value.'</option>';
        		}
        	}
        ?>
	</select>
</div>
<div class="col-md-12"><button class="btn btn-primary float-right" id="search_filter">Search</button></div>

</div>


<!--row-->
<div class="row mb-12">
<div class="col-md-12 mb-3">
<div class=""><div class="">
<div class="table-responsive">
<table  class="table table-striped" id="zero_configuration_table" style="width:100%">
<thead>
<tr>
<th scope="col">Sr. No.</th>
<th scope="col">Ticket No.</th>
<th scope="col">GA Number</th>
<th scope="col">Company Name</th>
<th scope="col">Complaint Type</th>
<th scope="col">Status</th>
<th scope="col">Created Date</th>
<th scope="col">Action</th>
</tr>
</thead>
<tbody>
 
</tbody>
</table>
</div>
</div>
</div>
</div>

</div>




<?php $this->load->view('common/footer');  ?>
<script src="<?php echo base_url('assets/dist-assets/js/plugins/datatables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.js') ?>"></script>
<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script type="text/javascript">
	

  $(document).ready(function(){
	   	var ga_no_list =<?php echo json_encode($projects );?>;
		$( "#ga_no" ).autocomplete({
	      		source: ga_no_list,
	      		 focus: function( event, ui ) {
	                  $( "#ga_no" ).val( ui.item.value );
	                    return false;
	               },
	      		select: function( event, ui ) {
	      			console.log(ui.item);
	      			$( "#ga_no" ).val( ui.item.value );
	                return false;
	            }
	    });

    	var table =  $('#zero_configuration_table').DataTable({
	            'processing': true,
	            "oLanguage": {'sProcessing': '<div class="dt_spinner"> <span class="spinner spinner-primary mr-3"></span></div>'},
		        "stripeClasses": [],
		        "lengthMenu": [10, 25, 75, 100,200],
		        "pageLength": 10,
		        "sDom": 'lrtip',
		        "bInfo":true,
		        "searching": true,
		        "ordering": false,
	         	"columnDefs": [{
	              	"defaultContent": "-",
	              	"targets": "_all"
	            }], 
	            serverSide: true,
	            ajax: {
			        url: "<?php echo site_url('complaint/list') ?>",
			        // dataSrc :'data',
			        dataFilter: function(data){
			            var json = jQuery.parseJSON( data );
			            json.recordsTotal = json.data.totalRecords;
			            json.recordsFiltered = json.data.totalRecordwithFilter;

			            json.data = json.data.aaData;
			 			
			            return JSON.stringify( json ); // return JSON string
			        },
			       data: function ( d ) {
				        return $.extend( {}, d, {
				           "ga_no": 	$("#ga_no").val().toLowerCase(),
				           "ticket_no": $("#ticket_no").val().toLowerCase(),
				           "complaint_type": $('#complaint_type').find(':selected').val(),
				           "status": $("#status").val()
				        } );
				    }
			    },

	            columns: [
	                    {
					      "render": function(data, type, full, meta) {
					      	return meta.row + meta.settings._iDisplayStart + 1;
					      }
					    },
	                    { data: 'ticket_no' },
	                    { data: 'ga_no' },
	                    { data: 'company' },
	                    { data: 'complaint_type' },
	                    { data: 'status' },
	                  /*  {
					      "render": function(data, type, full, meta) {
					        $html = '';
					        if(full.status == 0){
					        	$html += '<span class="badge badge-danger">Deactive</span>'
					        }
					        else if(full.status == 1){
					        	$html += '<span class="badge badge-success">Active</span>'
					        }
							return $html;
					      }
					    },*/

	                    { data: 'created_at' },
	                    {
					      "render": function(data, type, full, meta) {
					        $html = '<a class="text-success mr-2 text-18" href="<?php echo site_url() ?>complaint/'+full.id+'" data-toggle="tooltip" data-placement="top" title="View"><i class="nav-icon i-Eye font-weight-bold"></i></a>';
							return $html;
					      }
					    },
                 ],
                 "drawCallback": function(settings) {
				   stopLoading($('#search_filter'));
				},
        });   

    	$('#search_filter').click(function(){
    		showLoading($(this));
    		table.draw();
    	});

    	$('.export').click(function(e){
    		e.preventDefault();
    		$url = $(this).attr('href');
    		var params = { 
    			ga_no : $("#ga_no").val().toLowerCase(), 
    			ticket_no : $("#ticket_no").val().toLowerCase(),
    			complaint_type : $('#complaint_type').find(':selected').val(),
    			status : $("#status").val()
    		};

    		$url = $url+'?'+$.param( params );
    		window.location.href= $url;
    	});
    });
</script>