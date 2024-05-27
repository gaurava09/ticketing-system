<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo base_url('assets/dist-assets/css/plugins/datatables.min.css') ?>" />
<link href="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet" type="text/css">

    
<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">All Tickets</h1></div>
	<div class="float-right">
		<a class="btn btn-primary export" type="button" href="<?php echo site_url('complaint/export'); ?>">Export</a>

		<?php if($this->role == 'admin' || $this->role == 'super_admin') { ?>
			<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('complaint/create'); ?>"><i class="i-Add-File"></i> Add</a>
		<?php } ?>
		
	</div>
	<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>


<div class="row mb-12 search_div">
	
<div class="col-md-12 ">
<p>Search Tickets</p>
</div>
<!-- <div class="col-md-3 mb-3"><input class="form-control" id=" " type="text" placeholder="Company Name"></div> -->
<div class="col-md-3 mb-3"><input class="form-control" id="ticket_no" type="text" placeholder="Ticket No."></div>
<!-- <div class="col-md-3 mb-3"><input class="form-control" id="ga_no" type="text" placeholder="GA Number"></div> -->
<!-- <div class="col-md-3 mb-3"><input class="form-control" id="company" type="text" placeholder="Company Name"></div> -->
<div class="col-md-3 mb-3">
 	<select class="form-control" id="complaint_type" >
    	<option value="">Select Ticket Type</option>
        <?php
        	$complaint_types = complaint_types();
        	foreach ($complaint_types as $key => $value) {
        		echo '<option value="'.$key.'">'.$value.'</option>';
        	}
        ?>
    </select>
</div>

<!-- <div class="col-md-3 mb-3">
 	<select class="form-control" id="classification" >
    	<option value="">Select Classification</option>
        <?php
        	$classifications = classifications();
        	foreach ($classifications as $key => $value) {
        		echo '<option value="'.$key.'">'.$key.' - '.$value.'</option>';
        	}
        ?>
    </select>
</div> -->

<?php if($this->role == 'admin' || $this->role == 'super_admin') { ?>
	<div class="col-md-3 mb-3">
 	<select class="form-control" id="feedback" >
    	<option value="">Feedback Submitted ?</option>
    	<?php
    		$url_feedback = (isset($_GET['feedback'])) ? $_GET['feedback'] :'';

    		if($url_feedback == '1'){
    			echo '<option value="1" selected>Yes</option>';
    		}else{
        		echo '<option value="1">Yes</option>';
    		}

    		if($url_feedback == '0'){
    			echo '<option value="0" selected>No</option>';
    		}else{
        		echo '<option value="0">No</option>';
    		}
    	?>
    </select>
</div>
<?php } else { ?>

<div class="col-md-3 mb-3 d-none1">
	<select class="form-control" id="action">
		<option value="">Action Taken?</option>
		<?php
    		$url_feedback = (isset($_GET['action'])) ? $_GET['action'] :'';

    		if($url_feedback == 'yes'){
    			echo '<option value="yes" selected>Yes</option>';
    		}else{
        		echo '<option value="yes">Yes</option>';
    		}

    		if($url_feedback == 'no'){
    			echo '<option value="no" selected>No</option>';
    		}else{
        		echo '<option value="no">No</option>';
    		}
    	?>
	</select>
</div>
<?php }  ?>

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
<!-- <th scope="col">GA Number</th> -->
<th scope="col">Company Name</th>
<th scope="col">Ticket Type</th>
<!-- <th scope="col">Classification</th> -->
<?php if($this->role == 'admin' || $this->role == 'super_admin') { ?>
<th scope="col abc">Feedback Submitted</th>
<?php } ?>

<?php if($this->role == 'employee') { ?>
<th scope="col abc">Action Taken</th>
<?php } ?>
<th scope="col">Status</th>
<th scope="col">Assigned By</th>
<th scope="col">Created By</th>
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

  		var status_list =<?php echo json_encode($status_list );?>;

	 //   	var ga_no_list =<?php echo json_encode($projects );?>;
		// $( "#ga_no" ).autocomplete({
	 //      		source: ga_no_list,
	 //      		 focus: function( event, ui ) {
	 //                  $( "#ga_no" ).val( ui.item.value );
	 //                    return false;
	 //               },
	 //      		select: function( event, ui ) {
	 //      			console.log(ui.item);
	 //      			$( "#ga_no" ).val( ui.item.value );
	 //                return false;
	 //            }
	 //    });

		$('#feedback').on('change',function(){
			if($(this).val() == 0){
				$('#status').val(4);
			}
		});

    	var table =  $('#zero_configuration_table').DataTable({
	            'processing': true,
	            "oLanguage": {'sProcessing': '<div class="dt_spinner"> <span class="spinner spinner-primary mr-3"></span></div>'},
		        "stripeClasses": [],
		        "lengthMenu": [20, 40, 80, 100,200],
		        "pageLength": 20,
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
			        	console.log(data);
			            var json = jQuery.parseJSON( data );
			            json.recordsTotal = json.data.totalRecords;
			            json.recordsFiltered = json.data.totalRecordwithFilter;

			            json.data = json.data.aaData;
			 			
			            return JSON.stringify( json ); // return JSON string
			        },
			       data: function ( d ) {
				        return $.extend( {}, d, {
				           //"ga_no": 	$("#ga_no").val().toLowerCase(),
				           "ticket_no": $("#ticket_no").val().toLowerCase(),
				           "complaint_type": $('#complaint_type').find(':selected').val(),
				           "status": $("#status").val(),
				           //"classification": $("#classification").val(),
				           "feedback": $("#feedback").val(),
				           "action": $("#action").val(),
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
	                    //{ data: 'ga_no' },
	                    { data: 'company' },
	                    { data: 'complaint_type' },
	                    //{ data: 'classification' },
	                     <?php if($this->role == 'admin' || $this->role == 'super_admin') { ?>
	                    { data: 'feedback' },
	                     <?php } ?>

	                     <?php if($this->role == 'employee') { ?>
	                    { data: 'action_taken' },
	                     <?php } ?>
	                    {
					      "render": function(data, type, full, meta) {
					      	switch (full.status) {
			    				case '0': $class = 'badge-danger '; break; //deleted
			    				case '1': $class = 'badge-success '; break; //completed
			    				case '2': $class = 'badge-info '; break; //open
			    				case '3': $class = 'badge-warning '; break; //ongoing
			    				case '4': $class = 'badge-success '; break;//closed
			    				default: $class = ''; break;
			    			}

					        $html = '<span class="badge '+$class+'">'+status_list[full.status]+'</span>';
							return $html;
					      }
					    },

	                    { data: 'assigned_emp' },
	                    { data: 'created_by' },
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
    			//ga_no : $("#ga_no").val().toLowerCase(), 
    			ticket_no : $("#ticket_no").val().toLowerCase(),
    			complaint_type : $('#complaint_type').val(),
    			status : $("#status").val(),
    			//classification : $("#classification").val(),
    			feedback : $("#feedback").val(),
    			action : $("#action").val(),
    		};

    		$url = $url+'?'+$.param( params );
    		window.location.href= $url;
    	});
    });
</script>